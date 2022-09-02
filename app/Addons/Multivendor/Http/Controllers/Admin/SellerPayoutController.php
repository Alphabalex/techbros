<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerPayout;
use App\Models\Shop;

class SellerPayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_payouts'])->only('index');
        $this->middleware(['permission:show_payout_requests'])->only('payout_requests');
    }

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

        if ($request->shop_id) {
            $shop_id = $request->shop_id;
        }

        $payouts = SellerPayout::with('shop')->where('status', 'paid');
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $payouts = $payouts->whereDate('created_at', '>=', $date_range1[0]);
            $payouts = $payouts->whereDate('created_at', '<=', $date_range1[1]);
        }
        if ($shop_id) {
            $payouts = $payouts->where('shop_id', $shop_id);
        }

        $payouts = $payouts->latest()->paginate(20);

        return view('addon:multivendor::admin.seller_payouts.payout_history', compact('payouts', 'date_range', 'shop_id'));
    }

    public function payout_requests()
    {
        $payout_requests = SellerPayout::where('status', 'requested')->latest()->paginate(15);
        return view('addon:multivendor::admin.payout_requests.index', compact('payout_requests'));
    }


    public function payment_modal(Request $request)
    {
        $seller_withdraw_request = SellerPayout::where('id', $request->id)->first();
        $shop = $seller_withdraw_request->shop;
        return view('addon:multivendor::admin.payout_requests.payment_modal', compact('shop', 'seller_withdraw_request'));
    }

    public function pay_to_seller(Request $request)
    {
        $shop = Shop::find($request->shop_id);

        if ($shop && $shop->current_balance < $request->amount) {
            flash(translate('You can not pay more than seller balance'))->error();
            return back();
        }

        if ($request->has('withdraw_request_id')) {
            $payment = SellerPayout::find($request->withdraw_request_id);
        } else {
            $payment = new SellerPayout;
        }

        $payment->shop_id = $shop->id;
        $payment->status = 'paid';
        $payment->paid_amount = $request->amount;
        $payment->payment_method = $request->payment_option;
        $payment->txn_code = $request->txn_code;
        $payment->save();

        $shop->current_balance = $shop->current_balance - $request->amount;
        $shop->save();

        flash(translate('Payment completed'))->success();
        return back();
    }
}
