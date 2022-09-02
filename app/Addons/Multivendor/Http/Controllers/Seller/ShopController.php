<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Addons\Multivendor\Http\Services\ShopService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Shop::with('products')->where('user_id', auth()->user()->id)->first();
        return view('addon:multivendor::seller.shop_settings', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        if($id != auth()->user()->shop_id){
            abort(403);
        }
        (new ShopService)->update($request,$id);

        return back();
    }
}
