<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;
use App\Models\SellerPackageTranslation;
use App\Models\Shop;
use App\Models\User;
use Artisan;
use Carbon\Carbon;

class SellerPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_seller_packages'])->only('index');
        $this->middleware(['permission:show_seller_package_payments'])->only('package_purchase_history');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller_packages = SellerPackage::all();
        return view('addon:multivendor::admin.seller_packages.index', compact('seller_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addon:multivendor::admin.seller_packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seller_package = new SellerPackage;
        $seller_package->name = $request->name;
        $seller_package->amount = $request->amount;
        $seller_package->product_upload_limit = $request->product_upload_limit;
        $seller_package->commission = $request->commission;
        $seller_package->duration = $request->duration;
        $seller_package->logo = $request->logo;
        if ($seller_package->save()) {

            $seller_package_translation = SellerPackageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'seller_package_id' => $seller_package->id]);
            $seller_package_translation->name = $request->name;
            $seller_package_translation->save();

            flash(translate('Package has been added successfully'))->success();
            return redirect()->route('admin.seller_packages.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $seller_package = SellerPackage::findOrFail($id);
        return view('addon:multivendor::admin.seller_packages.edit', compact('seller_package', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $seller_package = SellerPackage::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $seller_package->name = $request->name;
        }
        $seller_package->amount = $request->amount;
        $seller_package->product_upload_limit = $request->product_upload_limit;
        $seller_package->commission = $request->commission;
        $seller_package->duration = $request->duration;
        $seller_package->logo = $request->logo;
        if ($seller_package->save()) {

            $seller_package_translation = SellerPackageTranslation::firstOrNew(['lang' => $request->lang, 'seller_package_id' => $seller_package->id]);
            $seller_package_translation->name = $request->name;
            $seller_package_translation->save();
            flash(translate('Package has been updated successfully'))->success();
            return redirect()->route('admin.seller_packages.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller_package = SellerPackage::findOrFail($id);
        foreach ($seller_package->seller_package_translations as $key => $seller_package_translation) {
            $seller_package_translation->delete();
        }
        SellerPackage::destroy($id);
        flash(translate('Package has been deleted successfully'))->success();
        return redirect()->route('admin.seller_packages.index');
    }

    public function package_purchase_history()
    {
        $package_payments =  SellerPackagePayment::latest()->paginate(20);
        return view('addon:multivendor::admin.seller_packages.payment_history', compact('package_payments'));
    }

    public function check_seller_package_validation(Request $request)
    {
        $admin = User::where('user_type', 'admin')->first();

        cache_clear();

        foreach (Shop::where('id', '!=', $admin->shop_id)->with(['products', 'seller_package'])->get() as $shop) {
            if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) != 'active') {
                foreach ($shop->products as $product) {
                    $product->published = 0;
                    $product->save();
                }
                $shop->seller_package_id = null;
                $shop->package_invalid_at = null;
                $shop->published = 0;
                $shop->save();
            }
        }
    }
}
