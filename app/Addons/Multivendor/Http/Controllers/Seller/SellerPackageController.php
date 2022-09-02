<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Http\Request;
use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;
use App\Models\Shop;
use Auth;
use Carbon\Carbon;

class SellerPackageController extends Controller
{
    
    public function select_package(){
        $seller_packages = SellerPackage::all();
        return view('addon:multivendor::seller.package.package_select', compact('seller_packages'));
    }
    

    public function package_purchase(Request $request){

        $seller_package = SellerPackage::findOrFail($request->seller_package_id);

        if ($seller_package->product_upload_limit < Auth::user()->shop->products->count()){
            flash(translate('You have more uploaded products than this package limit. You need to remove excessive products to downgrade.'))->warning();
            return back();
        }

        if($seller_package->amount == 0){
            return $this->purchase_payment_done($seller_package->id, null, null);
        }else{

            $request->redirect_to = null;
            $request->amount = $seller_package->amount;
            $request->payment_method = $request->payment_option;
            $request->payment_type = 'seller_package_payment';
            $request->user_id = auth()->user()->id;
            $request->order_code = null;
            $request->seller_package_id = $request->seller_package_id;  

            return (new PaymentController())->payment_initialize($request,$request->payment_option);
        }
    }

    public function purchase_payment_done($package_id, $payment_method, $payment_data){
        $shop = Auth::user()->shop;
        $seller_package = SellerPackage::findOrFail($package_id);
        $shop->seller_package_id = $seller_package->id;
        $shop->product_upload_limit = $seller_package->product_upload_limit;
        $shop->commission = $seller_package->commission;
        $shop->published = 1;
        $shop->package_invalid_at = date('Y-m-d', strtotime($seller_package->duration .'days'));
        $shop->save();

        if($payment_method != null){
            $seller_package_payment = new SellerPackagePayment;
            $seller_package_payment->user_id = Auth::user()->id;
            $seller_package_payment->seller_package_id = $package_id;
            $seller_package_payment->amount = $seller_package->amount;
            $seller_package_payment->payment_method = $payment_method;
            $seller_package_payment->payment_details = $payment_data;
            $seller_package_payment->save();
        }

        flash(translate('Package purchasing successful'))->success();
        return redirect()->route('seller.dashboard');
    }

    public function package_purchase_history()
    {
        $package_payments =  SellerPackagePayment::where('user_id', Auth::user()->id)->latest()->paginate(20);
        return view('addon:multivendor::seller.package.payment_history', compact('package_payments'));
    }

    
}
