<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Notifications\EmailVerificationNotification;
use Artisan;
use Hash;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:show_seller_orders'])->only('seller_orders');
        $this->middleware(['permission:show_sellers'])->only('all_sellers');
    }

    // Admin Panel
    public function all_sellers(Request $request)
    {
        $admin = User::where('user_type', 'admin')->first();

        $sort_search = null;
        $approved = null;
        $shops = Shop::withCount('products')->with(['user', 'seller_package'])->where('id', '!=', $admin->shop_id);

        if ($request->has('approved_status') && $request->approved_status != null) {
            $approved = $request->approved_status;
            $shops = $shops->where('approval', $approved);
        }

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $shops = $shops->where('name', 'like', '%' . $sort_search . '%')
                ->orWhere('phone', 'like', '%' . $sort_search . '%')
                ->orWhereHas('user', function ($query) use ($sort_search) {
                    $query->where('name', 'like', '%' . $sort_search . '%');
                });
        }

        $shops = $shops->latest()->paginate(15);
        return view('addon:multivendor::admin.sellers.index', compact('shops', 'sort_search', 'approved'));
    }

    public function seller_create()
    {
        return view('addon:multivendor::admin.sellers.create');
    }

    public function seller_store(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            flash(translate('Email already exists!'))->error();
            return back();
        }
        if ($request->password == $request->confirm_password) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = "seller";
            $user->password = Hash::make($request->password);

            if ($user->save()) {
                if (get_setting('email_verification') != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                } else {
                    $user->notify(new EmailVerificationNotification());
                }
                $user->save();

                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->name = $request->shop_name;
                $shop->slug = preg_replace('/\s+/', '-', $request->shop_name) . '-' . $shop->id;
                $shop->save();

                flash(translate('Seller has been added successfully'))->success();
                return redirect()->route('admin.all_sellers');
            }
            flash(translate('Something went wrong'))->error();
            return back();
        } else {
            flash("Password and confirm password didn't match")->warning();
            return back();
        }
    }



    public function seller_edit($id)
    {
        $seller = User::findOrFail(decrypt($id));
        return view('addon:multivendor::admin.sellers.edit', compact('seller'));
    }

    public function seller_update(Request $request)
    {
        $user = User::findOrFail($request->seller_id);
        $user->name = $request->name;
        $user->email = $request->email;
        if (User::where('id', '!=', $user->id)->where('email', $request->email)->first() == null) {
            if (strlen($request->password) > 0) {
                $user->password = Hash::make($request->password);
            }
            if ($user->save()) {
                flash(translate('Seller has been updated successfully'))->success();
                return redirect()->route('admin.all_sellers');
            }

            flash(translate('Something went wrong'))->error();
            return back();
        } else {
            flash(translate('Email Already Exists!'))->error();
            return back();
        }
    }

    public function seller_destroy($id)
    {
        $user = User::findOrFail($id);

        if (!is_null($user->products)) {
            foreach ($user->products as $product) {
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

                $product->delete();
            }
        }

        Shop::where('user_id', $user->id)->delete();

        if (User::destroy($id)) {
            flash(translate('Seller has been deleted successfully'))->success();
            return redirect()->route('admin.all_sellers');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }


    public function update_seller_approval(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->approval = $request->status;

        cache_clear();

        if ($shop->save()) {
            return 1;
        }
        return 0;
    }

    public function update_shop_publish(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->published = $request->status;

        cache_clear();

        if ($shop->save()) {
            return 1;
        }
        return 0;
    }


    public function profile_modal(Request $request)
    {
        $seller = User::findOrFail($request->id);
        return view('addon:multivendor::admin.sellers.profile_modal', compact('seller'));
    }

    public function payment_modal(Request $request)
    {
        $seller = User::findOrFail($request->id);
        return view('addon:multivendor::admin.seller_payouts.payment_modal', compact('seller'));
    }


    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $shop_id = null;

        $admin = User::where('user_type', 'admin')->first();
        $products = Product::orderBy('created_at', 'desc')->where('shop_id', '!=', $admin->shop->id);

        if ($request->shop_id != null) {
            $shop_id = $request->shop_id;
            $products = $products->where('shop_id', $shop_id);
        }

        if ($request->search != null) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->paginate(15);
        $type = 'All';

        return view('addon:multivendor::admin.seller_products', compact('products', 'type', 'col_name', 'query', 'sort_search', 'shop_id'));
    }

    public function seller_orders(Request $request)
    {

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $shop_id = null;

        $admin = User::where('user_type', 'admin')->first();
        $orders = Order::with(['combined_order', 'shop'])
            ->where('shop_id', '!=', $admin->shop_id);
        $shops = Shop::where('id', '!=', $admin->shop_id)->get();

        if ($request->shop_id != null) {
            $shop_id = $request->shop_id;
            $orders = $orders->where('shop_id', $shop_id);
        }
        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $orders = $orders->whereHas('combined_order', function ($query) use ($sort_search) {
                $query->where('code', 'like', '%' . $sort_search . '%');
            });
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
        return view('addon:multivendor::admin.seller_orders', compact('shops', 'orders', 'payment_status', 'delivery_status', 'sort_search', 'shop_id'));
    }
}
