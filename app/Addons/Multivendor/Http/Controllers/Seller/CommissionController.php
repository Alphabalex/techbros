<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function commission_history(Request $request) {
        $shop_id = null;
        $date_range = null;
        
        $shop_id = auth()->user()->shop_id;
        
        $commission_history = CommissionHistory::where('shop_id', '=', $shop_id);
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->whereDate('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->whereDate('created_at', '<=', $date_range1[1]);
        }
        
        $commission_history = $commission_history->paginate(10);
        
        return view('addon:multivendor::seller.earnings.commission_history', compact('commission_history', 'shop_id', 'date_range'));
    }

}
