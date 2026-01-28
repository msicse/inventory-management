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
use App\Models\User;
use App\Models\UserLog;
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
        // Load all filter options with ordering for better UX
        $types = Producttype::orderBy('name')->get();
        $stores = Store::orderBy('name')->get();
        $suppliers = Supplier::orderBy('company')->get();
        $models = Product::orderBy('model')->get();
        $departments = Department::orderBy('name')->get();

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

        return view('backend.report.stock-details')->with(compact('stocks', 'types', 'stores', 'suppliers', 'models', 'departments'));
    }

    public function inventorySearch(Request $request)
    {
        try {
            // Validate inputs
            $request->validate([
                'product_type' => 'nullable|integer|exists:producttypes,id',
                'product_model' => 'nullable|integer|exists:products,id',
                'store' => 'nullable|integer|exists:stores,id',
                'supplier' => 'nullable|integer|exists:suppliers,id',
                'department' => 'nullable|integer|exists:departments,id',
                'condition' => 'nullable|string|in:good,obsolete,damaged',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $type = $request->product_type;
            $model = $request->product_model;
            $condition = $request->condition;
            $store = $request->store;
            $supplier = $request->supplier;
            $department = $request->department;

            // Optimized subquery to get the latest transaction for each stock
            $latestTransactions = DB::table('transections')
                ->select('stock_id', DB::raw('MAX(id) as latest_transaction_id'))
                ->groupBy('stock_id');

            $query = Stock::select(
                'stocks.id as stock_id',
                'producttypes.name as product_type',
                'products.brand as product_brand',
                'products.model as product_model',
                'suppliers.company as supplier_company',
                'purchases.purchase_date as purchase_date',
                'purchases.invoice_no as purchase_invoice',
                'stocks.asset_tag',
                'stocks.service_tag',
                'stocks.asset_condition',
                'stocks.expired_date',
                'stores.name as store_name',
                'stocks.is_assigned',
                'latest_trans.return_date',
                'employees.name as employee_name',
                'departments.name as department_name',
                DB::raw('GREATEST(DATEDIFF(stocks.expired_date, CURDATE()), 0) as warranty_remaining'),
                DB::raw('CASE
                            WHEN stocks.is_assigned = 1 AND latest_trans.return_date IS NULL THEN employees.name
                            ELSE stores.name
                        END as assigned_to')
            )
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->join('producttypes', 'stocks.producttype_id', '=', 'producttypes.id')
            ->join('purchases', 'stocks.purchase_id', '=', 'purchases.id')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->join('stores', 'stocks.store_id', '=', 'stores.id')
            ->leftJoinSub($latestTransactions, 'latest_trans_map', function($join) {
                $join->on('stocks.id', '=', 'latest_trans_map.stock_id');
            })
            ->leftJoin('transections as latest_trans', 'latest_trans.id', '=', 'latest_trans_map.latest_transaction_id')
            ->leftJoin('employees', 'latest_trans.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id');

            // Apply filters
            if ($type) {
                $query->where('stocks.producttype_id', $type);
            }

            if ($model) {
                $query->where('stocks.product_id', $model);
            }

            // Show all items at the selected location (both assigned and unassigned)
            if ($store) {
                $query->where('stocks.store_id', $store);
            }

            if ($supplier) {
                $query->where('purchases.supplier_id', $supplier);
            }

            if ($condition) {
                $query->where('stocks.asset_condition', $condition);
            }

            if ($department) {
                $query->where('departments.id', $department);
            }

            // Date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('purchases.purchase_date', [
                    $request->input('start_date'),
                    $request->input('end_date'),
                ]);
            }

            // Group by to ensure each stock appears only once (MySQL strict mode compatible)
            $query->groupBy(
                'stocks.id',
                'producttypes.name',
                'products.brand',
                'products.model',
                'suppliers.company',
                'purchases.purchase_date',
                'purchases.invoice_no',
                'stocks.asset_tag',
                'stocks.service_tag',
                'stocks.asset_condition',
                'stocks.expired_date',
                'stores.name',
                'stocks.is_assigned',
                'latest_trans.return_date',
                'employees.name',
                'departments.name'
            );

            // Add ordering for better user experience
            $query->orderBy('purchases.purchase_date', 'desc')
                  ->orderBy('stocks.asset_tag', 'asc');

            return DataTables::of($query)->make(true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Inventory search error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while fetching inventory data',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }


    public function userLogs(Request $request){

        $users = User::all();
        $actions = UserLog::select('action')->distinct()->get();
        return view('backend.report.logs')->with(compact('users', 'actions'));
    }

    public function userLogsSearch(Request $request){
        $user = $request->user;
        $action = $request->action;

        $query = UserLog::select(
            'user_logs.*',
            'users.name as user_name'
        )->join('users', 'user_logs.user_id', '=', 'users.id');

        if ($user) {
            $query->where('user_id', $user);
        }
        if ($action) {
            $query->where('action', $action);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('user_logs.created_at', [
                $request->input('start_date'),
                $request->input('end_date'),
            ]);
        }

        // $user_logs = $query->get();

        return DataTables::of($query)->make(true);


    }
}
