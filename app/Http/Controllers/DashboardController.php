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
        
        $employees = Employee::all();
        $alaptops = Stock::where('product_status', 1)->get();
        $dlaptops = Stock::where('product_status', 3)->get();
        $plaptops = Stock::where('product_status', 2)->get();
        $total = Stock::count();

        $purchase = Purchase::select('*')
            ->whereMonth('purchase_date', Carbon::now()->month)
            ->count();

        $total_assigned = Stock::where('is_assigned', 1)->count();
        $total_remain = Stock::where('is_assigned', 2)->count();

        // return $purchase;
        
        
        
        // $expDate = Carbon::now();
        // return $expDate;

        // $data = Stock::whereDate('expired_date', '<',$expDate)->get(); 


        $expired_product = Stock::whereRaw('DATEDIFF(expired_date,current_date) < 90');

        // $data = DB::table('stocks')->whereRaw('extract(month from purchase_date) = ?', ['12'])->get();
        // return $expired_product;

        return view('backend.admin.dashboard')->with(compact('employees', 'alaptops', 'dlaptops', 'expired_product', 'purchase', 'total_assigned', 'total_remain', 'total' ));
    
    }
}
