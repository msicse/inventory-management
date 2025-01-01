<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Employee;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {

        $employees = Employee::where('status', 1);

        $purchase = Purchase::select('*')
            ->whereMonth('purchase_date', Carbon::now()->month)
            ->count();

        $total = Stock::count();
        $total_laptop = Stock::where('producttype_id', 1)->count();
        $total_mobile = Stock::where('producttype_id', 19)->count();


        $assigned_laptop = Stock::where('producttype_id', 1)->where('is_assigned',1)->count();
        $assigned_mobile = Stock::where('producttype_id', 19)->where('is_assigned',1)->count();

        $total_assigned = Stock::where('is_assigned', 1)->count();

        //return $assigned_laptop;


        $expired_product = Stock::whereRaw('DATEDIFF(expired_date,current_date) < 90');

        // $data = DB::table('stocks')->whereRaw('extract(month from purchase_date) = ?', ['12'])->get();
        // return $expired_product;

        return view('backend.admin.dashboard')->with(compact('employees', 'purchase', 'total', 'total_laptop', 'total_mobile', 'assigned_laptop', 'assigned_mobile', 'total_assigned', 'total', 'expired_product'));

    }
}
