<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\ProductVariation;
use App\Models\ProductVariationCombination;
use App\Models\Order;
use App\Models\ShopBrand;
use App\Utility\CategoryUtility;
use Artisan;
use Auth;
use Cache;
use Hash;
use DB;

class SellerController extends Controller
{


    // Seller panel
    public function seller_dashboard(Request $request)
    {
        $cached_graph_data = Cache::remember('cached_graph_data-' . auth()->user()->shop_id, 86400, function () {

            for ($i = 1; $i <= 12; $i++) {
                $item['sales_number_per_month'][$i] = Order::where('shop_id', Auth::user()->shop_id)->where('delivery_status', '!=', 'cancelled')->whereMonth('created_at', '=', $i)->whereYear('created_at', '=', date('Y'))->count();
                $item['sales_amount_per_month'][$i] = Order::where('shop_id', Auth::user()->shop_id)->where('delivery_status', '!=', 'cancelled')->whereMonth('created_at', '=', $i)->whereYear('created_at', '=', date('Y'))->sum('grand_total');
            }

            return $item;
        });

        return view('addon:multivendor::seller.dashboard', compact('cached_graph_data'));
    }

    public function profile()
    {
        return view('addon:multivendor::seller.profile');
    }

    public function profile_update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar = $request->avatar;
        if ($user->save()) {
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function seller_products_list(Request $request)
    {
        $search = null;
        $products = Product::where('shop_id', Auth::user()->shop->id)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('addon:multivendor::seller.products.index', compact('products', 'search'));
    }

    public function show_product_upload_form(Request $request)
    {
        $shop = Auth::user()->shop;
        if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'active' && $shop->products->count() < $shop->product_upload_limit) {
            $categories = Category::where('level', 0)->get();
            $attributes = Attribute::get();
            return view('addon:multivendor::seller.products.create', compact('categories', 'attributes'));
        } else {
            flash(translate("You don't have any active package. Please upgrade/renew your package."))->warning();
            return redirect()->route('seller.package_select');
        }
    }

    public function seller_product_store(Request $request)
    {
        $shop = auth()->user()->shop;
        if ($shop->seller_package_id == null || $shop->product_upload_limit <= $shop->products->count()) {
            flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
            return back();
        }

        if ($request->has('is_variant') && !$request->has('variations')) {
            flash(translate('Invalid product variations'))->error();
            return redirect()->back();
        }

        $product                    = new Product;
        $product->shop_id           = $shop->id;
        $product->name              = $request->name;
        $product->brand_id          = $request->brand_id;
        $product->unit              = $request->unit;
        $product->min_qty           = $request->min_qty;
        $product->max_qty           = $request->max_qty;
        $product->photos            = $request->photos;
        $product->thumbnail_img     = $request->thumbnail_img;
        $product->description       = $request->description;
        $product->published         = $request->status;

        // SEO meta
        $product->meta_title        = (!is_null($request->meta_title)) ? $request->meta_title : $product->name;
        $product->meta_description  = (!is_null($request->meta_description)) ? $request->meta_description : strip_tags($product->description);
        $product->meta_image        = (!is_null($request->meta_image)) ? $request->meta_image : $product->thumbnail_img;
        $product->slug              = Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));

        // warranty
        $product->has_warranty      = $request->has('has_warranty') && $request->has_warranty == 'on' ? 1 : 0;

        // tag
        $tags                       = array();
        if ($request->tags != null) {
            foreach (json_decode($request->tags) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags              = implode(',', $tags);

        // lowest highest price
        if ($request->has('is_variant') && $request->has('variations')) {
            $product->lowest_price  =  min(array_column($request->variations, 'price'));
            $product->highest_price =  max(array_column($request->variations, 'price'));
        } else {
            $product->lowest_price  =  $request->price;
            $product->highest_price =  $request->price;
        }

        // stock based on all variations
        $product->stock             = ($request->has('is_variant') && $request->has('variations')) ? max(array_column($request->variations, 'stock')) : $request->stock;

        // discount
        $product->discount          = $request->discount;
        $product->discount_type     = $request->discount_type;
        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        // shipping info
        $product->standard_delivery_time    = $request->standard_delivery_time;
        $product->express_delivery_time     = $request->express_delivery_time;
        $product->weight                    = $request->weight;
        $product->height                    = $request->height;
        $product->length                    = $request->length;
        $product->width                     = $request->width;

        $product->save();

        // Product Translations
        $product_translation = ProductTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'product_id' => $product->id]);
        $product_translation->name = $request->name;
        $product_translation->unit = $request->unit;
        $product_translation->description = $request->description;
        $product_translation->save();

        // category
        $product->categories()->sync($request->category_ids);

        // shop category ids
        $shop_category_ids = [];
        foreach ($request->category_ids ?? [] as $id) {
            $shop_category_ids[] = CategoryUtility::get_grand_parent_id($id);
        }
        $shop_category_ids =  array_merge($shop_category_ids, $product->shop->shop_categories->pluck('category_id')->toArray());
        $product->shop->categories()->sync(array_filter($shop_category_ids));

        // shop brand
        if ($request->brand_id) {
            ShopBrand::updateOrCreate([
                'shop_id' => $product->shop_id,
                'brand_id' => $request->brand_id,
            ]);
        }

        //taxes
        $tax_data = array();
        foreach ($request->taxes as $key => $tax) {
            array_push($tax_data, [
                'tax' => $tax,
                'tax_type' => $request->tax_types[$key]
            ]);
        }
        $taxes = array_combine($request->tax_ids, $tax_data);

        $product->product_taxes()->sync($taxes);

        //product variation
        $product->is_variant        = ($request->has('is_variant') && $request->has('variations')) ? 1 : 0;

        if ($request->has('is_variant') && $request->has('variations')) {
            foreach ($request->variations as $variation) {
                $p_variation              = new ProductVariation;
                $p_variation->product_id  = $product->id;
                $p_variation->code        = $variation['code'];
                $p_variation->price       = $variation['price'];
                $p_variation->stock       = $variation['stock'];
                $p_variation->sku         = $variation['sku'];
                $p_variation->img         = $variation['img'];
                $p_variation->save();

                foreach (array_filter(explode("/", $variation['code'])) as $combination) {
                    $p_variation_comb                         = new ProductVariationCombination;
                    $p_variation_comb->product_id             = $product->id;
                    $p_variation_comb->product_variation_id   = $p_variation->id;
                    $p_variation_comb->attribute_id           = explode(":", $combination)[0];
                    $p_variation_comb->attribute_value_id     = explode(":", $combination)[1];
                    $p_variation_comb->save();
                }
            }
        } else {
            $variation              = new ProductVariation;
            $variation->product_id  = $product->id;
            $variation->sku         = $request->sku;
            $variation->price       = $request->price;
            $variation->stock       = $request->stock;
            $variation->save();
        }

        // attribute
        if ($request->has('product_attributes') && $request->product_attributes[0] != null) {
            foreach ($request->product_attributes as $attr_id) {
                $attribute_values = 'attribute_' . $attr_id . '_values';
                if ($request->has($attribute_values) && $request->$attribute_values != null) {
                    $p_attribute = new ProductAttribute;
                    $p_attribute->product_id = $product->id;
                    $p_attribute->attribute_id = $attr_id;
                    $p_attribute->save();

                    foreach ($request->$attribute_values as $val_id) {
                        $p_attr_value = new ProductAttributeValue;
                        $p_attr_value->product_id = $product->id;
                        $p_attr_value->attribute_id = $attr_id;
                        $p_attr_value->attribute_value_id = $val_id;
                        $p_attr_value->save();
                    }
                }
            }
        }

        $product->save();

        flash(translate('Product has been inserted successfully'))->success();
        return redirect()->route('seller.products');
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->shop_id != auth()->user()->shop_id) {
            abort(403);
        }

        $lang = $request->lang;
        $categories = Category::where('level', 0)->get();
        $all_attributes = Attribute::get();
        return view('addon:multivendor::seller.products.edit', compact('product', 'categories', 'lang', 'all_attributes'));
    }

    public function seller_product_update(Request $request, $id)
    {
        if ($request->has('is_variant') && !$request->has('variations')) {
            flash(translate('Invalid product variations'))->error();
            return redirect()->back();
        }

        $product                    = Product::findOrFail($id);
        $oldProduct                 = clone $product;
        $shop                       = auth()->user()->shop;

        if ($product->shop_id != $shop->id) {
            abort(403);
        }

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $product->name          = $request->name;
            $product->unit          = $request->unit;
            $product->description   = $request->description;
        }

        $product->brand_id          = $request->brand_id;
        $product->min_qty           = $request->min_qty;
        $product->max_qty           = $request->max_qty;
        $product->photos            = $request->photos;
        $product->thumbnail_img     = $request->thumbnail_img;
        $product->published         = $request->status;

        // Product Translations
        $product_translation                = ProductTranslation::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $product_translation->name          = $request->name;
        $product_translation->unit          = $request->unit;
        $product_translation->description   = $request->description;
        $product_translation->save();


        // SEO meta
        $product->meta_title        = (!is_null($request->meta_title)) ? $request->meta_title : $product->name;
        $product->meta_description  = (!is_null($request->meta_description)) ? $request->meta_description : strip_tags($product->description);
        $product->meta_image        = (!is_null($request->meta_image)) ? $request->meta_image : $product->thumbnail_img;
        $product->slug              = (!is_null($request->slug)) ? Str::slug($request->slug, '-') : Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));

        // warranty
        $product->has_warranty      = $request->has('has_warranty') && $request->has_warranty == 'on' ? 1 : 0;


        // tag
        $tags                       = array();
        if ($request->tags != null) {
            foreach (json_decode($request->tags) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags              = implode(',', $tags);

        // lowest highest price
        if ($request->has('is_variant') && $request->has('variations')) {
            $product->lowest_price  =  min(array_column($request->variations, 'price'));
            $product->highest_price =  max(array_column($request->variations, 'price'));
        } else {
            $product->lowest_price  =  $request->price;
            $product->highest_price =  $request->price;
        }

        // stock based on all variations
        $product->stock             = ($request->has('is_variant') && $request->has('variations')) ? max(array_column($request->variations, 'stock')) : $request->stock;

        // discount
        $product->discount          = $request->discount;
        $product->discount_type     = $request->discount_type;
        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        // shipping info
        $product->standard_delivery_time    = $request->standard_delivery_time;
        $product->express_delivery_time     = $request->express_delivery_time;
        $product->weight                    = $request->weight;
        $product->height                    = $request->height;
        $product->length                    = $request->length;
        $product->width                     = $request->width;

        // category
        $product->categories()->sync($request->category_ids);

        // shop category ids
        $shop_category_ids = [];
        foreach ($request->category_ids ?? [] as $id) {
            $shop_category_ids[] = CategoryUtility::get_grand_parent_id($id);
        }
        $shop_category_ids =  array_merge($shop_category_ids, $product->shop->shop_categories->pluck('category_id')->toArray());
        $product->shop->categories()->sync(array_filter($shop_category_ids));

        // shop brand
        if ($request->brand_id) {
            ShopBrand::updateOrCreate([
                'shop_id' => $product->shop_id,
                'brand_id' => $request->brand_id,
            ]);
        }

        // taxes
        $tax_data = array();
        foreach ($request->taxes as $key => $tax) {
            array_push($tax_data, [
                'tax' => $tax,
                'tax_type' => $request->tax_types[$key]
            ]);
        }
        $taxes = array_combine($request->tax_ids, $tax_data);

        $product->product_taxes()->sync($taxes);


        //product variation
        $product->is_variant        = ($request->has('is_variant') && $request->has('variations')) ? 1 : 0;

        if ($request->has('is_variant') && $request->has('variations')) {

            $requested_variations = collect($request->variations);
            $requested_variations_code = $requested_variations->pluck('code')->toArray();
            $old_variations_codes = $product->variations->pluck('code')->toArray();
            $old_matched_variations = $requested_variations->whereIn('code', $old_variations_codes);
            $new_variations = $requested_variations->whereNotIn('code', $old_variations_codes);


            // delete old variations that didn't requested
            $product->variations->whereNotIn('code', $requested_variations_code)->each(function ($variation) {
                foreach ($variation->combinations as $comb) {
                    $comb->delete();
                }
                $variation->delete();
            });

            // update old matched variations
            foreach ($old_matched_variations as $variation) {
                $p_variation              = ProductVariation::where('product_id', $product->id)->where('code', $variation['code'])->first();
                $p_variation->price       = $variation['price'];
                $p_variation->stock       = $variation['stock'];
                $p_variation->sku         = $variation['sku'];
                $p_variation->img         = $variation['img'];
                $p_variation->save();
            }


            // insert new requested variations
            foreach ($new_variations as $variation) {
                $p_variation              = new ProductVariation;
                $p_variation->product_id  = $product->id;
                $p_variation->code        = $variation['code'];
                $p_variation->price       = $variation['price'];
                $p_variation->stock       = $variation['stock'];
                $p_variation->sku         = $variation['sku'];
                $p_variation->img         = $variation['img'];
                $p_variation->save();

                foreach (array_filter(explode("/", $variation['code'])) as $combination) {
                    $p_variation_comb                         = new ProductVariationCombination;
                    $p_variation_comb->product_id             = $product->id;
                    $p_variation_comb->product_variation_id   = $p_variation->id;
                    $p_variation_comb->attribute_id           = explode(":", $combination)[0];
                    $p_variation_comb->attribute_value_id     = explode(":", $combination)[1];
                    $p_variation_comb->save();
                }
            }
        } else {
            // check if old product is variant then delete all old variation & combinations
            if ($oldProduct->is_variant) {
                foreach ($product->variations as $variation) {
                    foreach ($variation->combinations as $comb) {
                        $comb->delete();
                    }
                    $variation->delete();
                }
            }

            $variation              = $product->variations->first();
            $variation->product_id  = $product->id;
            $variation->code        = null;
            $variation->sku         = $request->sku;
            $variation->price       = $request->price;
            $variation->stock       = $request->stock;
            $variation->save();
        }


        // attributes + values
        foreach ($product->attributes as $attr) {
            $attr->delete();
        }
        foreach ($product->attribute_values as $attr_val) {
            $attr_val->delete();
        }
        if ($request->has('product_attributes') && $request->product_attributes[0] != null) {
            foreach ($request->product_attributes as $attr_id) {
                $attribute_values = 'attribute_' . $attr_id . '_values';
                if ($request->has($attribute_values) && $request->$attribute_values != null) {
                    $p_attribute = new ProductAttribute;
                    $p_attribute->product_id = $product->id;
                    $p_attribute->attribute_id = $attr_id;
                    $p_attribute->save();

                    foreach ($request->$attribute_values as $val_id) {
                        $p_attr_value = new ProductAttributeValue;
                        $p_attr_value->product_id = $product->id;
                        $p_attr_value->attribute_id = $attr_id;
                        $p_attr_value->attribute_value_id = $val_id;
                        $p_attr_value->save();
                    }
                }
            }
        }


        $product->save();

        flash(translate('Product has been updated successfully'))->success();
        return redirect()->route('seller.products');
    }

    public function seller_product_show($id)
    {
        $product = Product::withCount('reviews', 'wishlists', 'carts')->with('variations.combinations')->findOrFail($id);
        if ($product->shop_id != auth()->user()->shop_id) {
            abort(403);
        }

        return view('addon:multivendor::seller.products.show', [
            'product' => $product
        ]);
    }

    public function seller_product_published(Request $request)
    {
        $shop = auth()->user()->shop;
        if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) != 'active') {
            return response()->json([
                'success' => false,
                'message' => translate('Please upgrade your package for changing status.')
            ]);
        }

        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        $product->save();

        cache_clear();

        return response()->json([
            'success' => true,
            'message' => translate('Products status updated successfully')
        ]);
    }

    public function seller_product_duplicate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->shop_id != auth()->user()->shop_id) {
            abort(403);
        }
        $product_new = $product->replicate();
        $product_new->slug = Str::slug($product_new->name, '-') . '-' . strtolower(Str::random(5));

        if ($product_new->save()) {

            // variation duplicate
            foreach ($product->variations as $key => $variation) {
                $p_variation              = new ProductVariation;
                $p_variation->product_id  = $product_new->id;
                $p_variation->code        = $variation->code;
                $p_variation->price       = $variation->price;
                $p_variation->stock       = $variation->stock;
                $p_variation->sku         = $variation->sku;
                $p_variation->img         = $variation->img;
                $p_variation->save();

                // variation combination duplicate
                foreach ($variation->combinations as $key => $combination) {
                    $p_variation_comb                         = new ProductVariationCombination;
                    $p_variation_comb->product_id             = $product_new->id;
                    $p_variation_comb->product_variation_id   = $p_variation->id;
                    $p_variation_comb->attribute_id           = $combination->attribute_id;
                    $p_variation_comb->attribute_value_id     = $combination->attribute_value_id;
                    $p_variation_comb->save();
                }
            }

            // attribute duplicate
            foreach ($product->attributes as $key => $attribute) {
                $p_attribute                = new ProductAttribute;
                $p_attribute->product_id    = $product_new->id;
                $p_attribute->attribute_id  = $attribute->attribute_id;
                $p_attribute->save();
            }

            // attribute value duplicate
            foreach ($product->attribute_values as $key => $attribute_value) {
                $p_attr_value                       = new ProductAttributeValue;
                $p_attr_value->product_id           = $product_new->id;
                $p_attr_value->attribute_id         = $attribute_value->attribute_id;
                $p_attr_value->attribute_value_id   = $attribute_value->attribute_value_id;
                $p_attr_value->save();
            }

            // translation duplicate
            foreach ($product->product_translations as $key => $translation) {
                $product_translation                = new ProductTranslation;
                $product_translation->product_id    = $product_new->id;
                $product_translation->name          = $translation->name;
                $product_translation->unit          = $translation->unit;
                $product_translation->description   = $translation->description;
                $product_translation->lang          = $translation->lang;
                $product_translation->save();
            }

            //categories duplicate
            foreach ($product->product_categories as $key => $category) {
                $p_category                 = new ProductCategory;
                $p_category->product_id     = $product_new->id;
                $p_category->category_id    = $category->category_id;
                $p_category->save();
            }

            // taxes duplicate
            foreach ($product->taxes as $key => $tax) {
                $p_tax                = new ProductTax;
                $p_tax->product_id    = $product_new->id;
                $p_tax->tax_id        = $tax->tax_id;
                $p_tax->tax           = $tax->tax;
                $p_tax->tax_type      = $tax->tax_type;
                $p_tax->save();
            }

            flash(translate('Product has been duplicated successfully'))->success();
            return redirect()->route('seller.products');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function seller_product_destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->shop_id != auth()->user()->shop_id) {
            abort(403);
        }

        $product->product_translations()->delete();
        $product->variations()->delete();
        $product->variation_combinations()->delete();
        $product->reviews()->delete();
        $product->product_categories()->delete();
        $product->carts()->delete();
        $product->offers()->delete();
        $product->wishlists()->delete();
        $product->attributes()->delete();
        $product->attribute_values()->delete();
        $product->taxes()->delete();

        if (Product::destroy($id)) {
            flash(translate('Product has been deleted successfully'))->success();
            return redirect()->route('seller.products');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function orders(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;

        $orders = Order::where('shop_id', Auth::user()->shop->id);

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        $orders = $orders->latest()->paginate(15);
        return view('addon:multivendor::seller.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function orders_show($id)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.variation.combinations'])->findOrFail($id);
        if ($order->shop_id != auth()->user()->shop_id) {
            abort(403);
        }
        return view('addon:multivendor::seller.orders.show', compact('order'));
    }

    public function seller_product_reviews(Request $request)
    {
        $reviews = DB::table('reviews')
            ->orderBy('id', 'desc')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.shop_id', Auth::user()->shop_id)
            ->select('reviews.id')
            ->distinct()
            ->paginate(10);
        return view('addon:multivendor::seller.product_reviews', compact('reviews'));
    }
}
