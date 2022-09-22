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
            return $this->purchase_payment_done($seller_package->id, 'FREE', null);
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
        if($payment_method != null){
            $shop = Auth::user()->shop;
            $seller_package = SellerPackage::findOrFail((int)$package_id);
            
            $seller_package_payment = new SellerPackagePayment;
            $seller_package_payment->user_id = Auth::user()->id;
            $seller_package_payment->seller_package_id = $package_id;
            $seller_package_payment->amount = $seller_package->amount;

            if (strpos($payment_method , 'offline_payment') !== false) {
                // save receipt
                if(session('receiptFile') != null) {
                    $seller_package_payment->reciept = session('receiptFile');
                } 
                
                // offline payment
                $seller_package_payment->approval = 0;
                $seller_package_payment->offline_payment = 1;
                $seller_package_payment->transaction_id = session('transactionId');
                $seller_package_payment->payment_details = json_decode($payment_data);
                $seller_package_payment->payment_method = session('manualPaymentMethod')->heading;
                
                flash(translate('Please wait for approval'))->success();
            }else{
                // 
                $shop->seller_package_id = $seller_package->id;
                $shop->package_invalid_at = date('Y-m-d', strtotime($seller_package->duration .'days'));
                $shop->product_upload_limit = $seller_package->product_upload_limit;
                $shop->commission = $seller_package->commission;
                $shop->published = 1;
                // online payment
                $seller_package_payment->approval = 1; 
                $seller_package_payment->offline_payment = 0;
                $seller_package_payment->payment_details = $payment_data;
                $seller_package_payment->payment_method = $payment_method;
                
                flash(translate('Package purchasing successful'))->success();
            }
            
            $shop->save();
            $seller_package_payment->save();
            return redirect()->route('seller.dashboard');
        }

        flash(translate('Package purchasing failed'))->error();
        return redirect()->route('seller.dashboard');
    }

    public function package_purchase_history()
    {
        $package_payments =  SellerPackagePayment::where('user_id', Auth::user()->id)->latest()->paginate(20);
        return view('addon:multivendor::seller.package.payment_history', compact('package_payments'));
    }

    
}
