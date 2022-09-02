<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerPayout;

class SellerPayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shop_id = null;
        $date_range = null;
        $shop_id = null;

        $shop_id = auth()->user()->shop_id;
        
        $payouts = SellerPayout::with('shop')->where('status', 'paid')->where('shop_id', $shop_id);

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $payouts = $payouts->whereDate('created_at', '>=', $date_range1[0]);
            $payouts = $payouts->whereDate('created_at', '<=', $date_range1[1]);
        }

        $payouts = $payouts->latest()->paginate(20);

        return view('addon:multivendor::seller.earnings.payouts', compact('payouts','date_range'));

    }

    public function payout_requests()
    {
        $payout_requests = SellerPayout::where('shop_id', auth()->user()->shop_id)->where('status','requested')->latest()->paginate(15);
        return view('addon:multivendor::seller.earnings.payout_requests', compact('payout_requests'));
    }


    public function store_withdraw_request(Request $request)
    {
        if(auth()->user()->shop->current_balance < $request->amount){
            flash(translate('You can not request more than your balance'))->error();
            return back();
        }

        $seller_withdraw_request = new SellerPayout;
        $seller_withdraw_request->shop_id = auth()->user()->shop_id;
        $seller_withdraw_request->requested_amount = $request->amount;
        $seller_withdraw_request->seller_note = $request->message;
        $seller_withdraw_request->status = 'requested';
        if ($seller_withdraw_request->save()) {
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('seller.payouts.request');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
    

    public function payout_settings()
    {
        $shop = auth()->user()->shop;
        return view('addon:multivendor::seller.earnings.money_withdraw_settings',compact('shop'));
    }

    public function payout_settings_update(Request $request){
        $shop = auth()->user()->shop;
        $shop->cash_payout_status = $request->cash_payout_status;
        $shop->bank_payout_status = $request->bank_payout_status;
        $shop->bank_name = $request->bank_name;
        $shop->bank_acc_name = $request->bank_acc_name;
        $shop->bank_acc_no = $request->bank_acc_no;
        $shop->bank_routing_no = $request->bank_routing_no;
        $shop->save();
        
        flash(translate('Your Money Withdraw Ssettings Successfully!'))->success();
        return back();
    }
}
