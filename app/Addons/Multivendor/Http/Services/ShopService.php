<?php

namespace App\Addons\Multivendor\Http\Services;

use App\Models\Shop;
use Str;

class ShopService
{
    public function update($request,$id){
        
        $shop = Shop::find($id);

        if($request->has('name') && $request->has('address')){

            $shop_old_name = $shop->name;
            $slug = Str::slug($request->name, '-');
            $same_slug_count = Shop::where('slug','LIKE',$slug.'%')->count();
            $slug_suffix = $same_slug_count > 0 ? '-'.$same_slug_count+1 : '';
            $slug .= $slug_suffix;
        
            $shop->name             = $request->name;
            $shop->address          = $request->address;
            $shop->phone            = $request->phone;
            $shop->slug             = $shop_old_name == $request->name ? $shop->slug : $slug;
            $shop->meta_title       = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo             = $request->logo;
        }
        else{
            $shop->banners = $request->banners;
            $shop->featured_products = $request->featured_products;
            $shop->products_banners = banner_array_generate($request->products_banners_images,$request->products_banners_links,false);
            $shop->banners_1 = banner_array_generate($request->banner_section_one_images,$request->banner_section_one_links,false);
            $shop->banners_2 = banner_array_generate($request->banner_section_two_images,$request->banner_section_two_links,false);
            $shop->banners_3 = banner_array_generate($request->banner_section_three_images,$request->banner_section_three_links,false);
            $shop->banners_4 = banner_array_generate($request->banner_section_four_images,$request->banner_section_four_links,false);
        }

        $shop->save();
        
        flash(translate('Your Shop has been updated successfully!'))->success();
    }
}