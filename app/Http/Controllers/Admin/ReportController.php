<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;

use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Department;
use App\Models\Producttype;
use App\Models\Transection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::orderBy('emply_id', 'asc')->get();
        $departments = Department::all();


        if (isset($request->department_id)) {
            $employees = Employee::where('department_id', $request->department_id)
                ->orderBy('emply_id', $request->order_by)
                ->get();
        }

        return view('backend.report.index')->with(compact('employees', 'departments'));
    }

    public function getReport(Request $request)
    {

        $transections = Transection::all();
        $employee = Employee::where('emply_id', $request->employee_id)->first();
        $employees = Employee::all();
        return view('backend.report.index')->with(compact('employee', 'employees'));
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        return view('backend.report.employee')->with(compact('employee'));
    }

    public function stocks(Request $request)
    {

        $stocks = Producttype::all();

        return view('backend.report.stocks')->with(compact('stocks'));
    }

    function stockDetails(Request $request, $id)
    {
        $stocks = Stock::where('producttype_id', $id)->get();

        $types = Producttype::all();
        $stores = Store::all();
        $suppliers = Supplier::all();
        $models = Product::all();

        return view('backend.report.stock-details')->with(compact('stocks', 'types', 'stores', 'suppliers', 'models'));
    }

    public function transections(Request $request)
    {

        // Query with filters
        $query = Transection::query();

        //return $request->all();

        $transections = Transection::orderBy('employee_id', 'asc')->get();
        $employees = Employee::all();

        if (isset($request->employee_id)) {

            $transections = Transection::where('employee_id', $request->employee_id)
                ->orderBy('employee_id', $request->order_by)->get();
            //return $transections;
        }

        return view('backend.report.transections')->with(compact('transections', 'employees'));
    }



    public function inventory(Request $request)
    {

        $types = Producttype::all();
        $stores = Store::all();
        $suppliers = Supplier::all();
        $models = Product::all();

        $type = $request->type;
        $model = $request->model;
        $condition = $request->condition;
        $store = $request->store;
        $supplier = $request->supplier;
        $from = $request->from;
        $to = $request->to;


        $query = Stock::query();

        if ($type) {
            $query->where('producttype_id', $type);
        }
        if ($model) {
            $query->where('product_id', $model);
        }
        if ($store) {
            $query->where('store_id', $store);
        }
        if ($store) {
            $query->where('store_id', $store);
        }
        if ($supplier) {
            $query->where('supplier_id', $supplier);
        }

        $stocks = $query->get();

        return view('backend.report.stock-details')->with(compact('stocks', 'types', 'stores', 'suppliers', 'models'));
    }

    public function inventorySearch(Request $request)
    {
        $type = $request->product_type;
        $model = $request->product_model;
        $condition = $request->condition;
        $store = $request->store;
        $supplier = $request->supplier;


        $query = Stock::select(
            'stocks.id as stock_id',
            'producttypes.name as product_type',
            'products.brand as product_brand',
            'products.model as product_model',
            'suppliers.company as supplier_company',
            'purchases.purchase_date as purchase_date',
            'purchases.invoice_no as purchase_invoice',
            'asset_tag',
            'service_tag',
            'asset_condition',
            'stocks.expired_date',
            'stores.name as store_name',
            'employees.name as employee_name',
            DB::raw('GREATEST(DATEDIFF(stocks.expired_date, CURDATE()), 0) as warranty_remaining'),
            DB::raw('CASE
                        WHEN stocks.is_assigned = 1 AND transections.return_date IS NULL THEN employees.name
                        ELSE stores.name
                    END as assigned_to')

        )
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->join('producttypes', 'stocks.producttype_id', '=', 'producttypes.id')
            ->join('purchases', 'stocks.purchase_id', '=', 'purchases.id')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->join('stores', 'stocks.store_id', '=', 'stores.id')
            ->leftJoin('transections', 'transections.stock_id', '=', 'stocks.id')
            ->leftJoin('employees', 'transections.employee_id', '=', 'employees.id');

        // return $query->get();


        if ($type) {
            $query->where('stocks.producttype_id', $type);
        }
        if ($model) {
            $query->where('stocks.product_id', $model);
        }

        if ($store) {
            $query->where(function ($q) use ($store) {
                $q->where('stocks.store_id', $store)
                  ->where('stocks.is_assigned', 2);
            });
        }

        if ($supplier) {
            $query->where('purchases.supplier_id', $supplier);
        }
        if ($condition) {
            $query->where('stocks.asset_condition', $condition);
        }

        // // Add filters dynamically
        // if ($request->has('type')) {
        //     $query->where('stocks.producttype_id', $request->input('type'));
        // }

        // if ($request->has('model')) {
        //     $query->where('stocks.product_id', $request->input('model'));
        // }
        // if ($request->has('condition')) {
        //     $query->where('stocks.condition', $request->input('condition'));
        // }
        // if ($request->has('store')) {
        //     $query->where('stocks.store_id', $request->input('store'));
        // }

        // if ($request->has('supplier')) {
        //     $query->where('purchases.supplier_id', $request->input('supplier'));
        // }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchases.purchase_date', [
                $request->input('start_date'),
                $request->input('end_date'),
            ]);
        }



        $stocks = $query->get();

        return DataTables::of($query)->make(true);
    }
}
