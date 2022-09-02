<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Addons\Multivendor\Http\Services\ShopService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_shop_setting'])->only('shop_setting', 'shop_setting_update');
    }

    public function shop_setting(Request $request)
    {
        $shop = Shop::with('products')->find(auth()->user()->shop_id);
        return view('addon:multivendor::admin.shop.shop_settings', compact('shop'));
    }
    public function shop_setting_update(Request $request, $id)
    {
        if ($id != auth()->user()->shop_id) {
            abort(403);
        }
        (new ShopService)->update($request, $id);

        return back();
    }
}
