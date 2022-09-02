<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_commission_log'])->only('commission_history');
    }

    public function commission_history(Request $request)
    {
        $shop_id = null;
        $date_range = null;

        if ($request->shop_id) {
            $shop_id = $request->shop_id;
        }

        $commission_history = CommissionHistory::query();

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->whereDate('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->whereDate('created_at', '<=', $date_range1[1]);
        }
        if ($shop_id) {
            $commission_history = $commission_history->where('shop_id', '=', $shop_id);
        }

        $commission_history = $commission_history->latest()->paginate(10);

        return view('addon:multivendor::admin.commission_history', compact('commission_history', 'shop_id', 'date_range'));
    }
}
