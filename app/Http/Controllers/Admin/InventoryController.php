<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\AssetStatus;

use App\Models\Producttype;
use App\Models\Transection;
use App\Imports\StockImport;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:inventory-list|inventory-create|inventory-edit|inventory-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:inventory-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:inventory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:inventory-delete', ['only' => ['destroy']]);
        $this->middleware('permission:inventory-update-tag', ['only' => ['pending_asset_tag', 'uploadBulk', 'update_tag']]);
    }
    public function index(Request $request)
    {
        // $inventories = Stock::all();
        $types = Producttype::all();
        $statuses = AssetStatus::all();
        $stores = Store::all();
        $suppliers = Supplier::all();
        $employees = Employee::where('status', 1)->get();
        $departments = Department::all();

        $type = $request->product_type;
        $condition = $request->condition;
        $store = $request->store;
        $supplier = $request->supplier;
        //{ data: 'assigned_to', name: 'employees.name' }, this is my datatable column, it showing if assigned show employeee name else show store name,  i have join query , i want to enable search from both table
        // return $query->get()
        if ($request->ajax()) {
            $query = Stock::select(
                'stocks.id as stock_id',
                'producttypes.name as product_type',
                'stocks.service_tag',
                'stocks.is_assigned',
                'stocks.asset_tag',
                'stocks.store_id',
                'stocks.asset_condition',
                'stocks.quantity',
                'stocks.purchase_date',
                'products.title as title',
                'stores.name as store_name',
                'employees.name as employee_name',
                'employees.emply_id as emply_id',
                'employees.id as employee_id',
                'suppliers.company as supplier_company',
                'transections.employee_id',
                DB::raw('CASE
                        WHEN stocks.is_assigned = 1 AND transections.return_date IS NULL THEN employees.id
                    END as assigned_id'),

                DB::raw('CASE
                        WHEN stocks.is_assigned = 1 AND transections.return_date IS NULL AND stores.id != 5 THEN employees.name
                        ELSE stores.name
                    END as assigned_to'),
            )
                ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
                ->leftJoin('stores', 'stocks.store_id', '=', 'stores.id')
                ->leftJoin('producttypes', 'stocks.producttype_id', '=', 'producttypes.id')
                ->leftJoin('purchases', 'stocks.purchase_id', '=', 'purchases.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->leftJoin('transections', 'transections.stock_id', '=', 'stocks.id')
                ->leftJoin('employees', 'transections.employee_id', '=', 'employees.id')
                ->when($type, fn($q) => $q->where('stocks.producttype_id', $type))
                ->when($store, fn($q) => $q->where(function ($q2) use ($store) {
                    $q2->where('stocks.store_id', $store)
                        ->where('stocks.is_assigned', 2);
                }))
                ->when($supplier, fn($q) => $q->where('purchases.supplier_id', $supplier))
                ->when($condition, fn($q) => $q->where('stocks.asset_condition', $condition));


            // if ($type) {
            //     $query->where('stocks.producttype_id', $type);
            // }
            // if ($store) {
            //     $query->where(function ($q) use ($store) {
            //         $q->where('stocks.store_id', $store)
            //             ->where('stocks.is_assigned', 2);
            //     });
            // }

            // if ($supplier) {
            //     $query->where('purchases.supplier_id', $supplier);
            // }

            // if ($condition) {
            //     $query->where('stocks.asset_condition', $condition);
            // }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    // Only add checkbox if asset_tag exists
                    if (!empty($row->asset_tag)) {
                        return '<input type="checkbox" class="stock-checkbox filled-in" value="' . $row->stock_id . '" id="check-' . $row->stock_id . '" />
                                <label for="check-' . $row->stock_id . '"></label>';
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    // Add your action buttons here
                    // return '
                    //     <a href="' . route('inventories.show', $row->stock_id) . '" class="btn btn-info btn-sm">View</a>asset_condition
                    //     <button type="button" class="btn btn-primary btn-sm open-popup" data-id="' . $row->stock_id . '" data-service-tag="'. $row->service_tag .'" data-store-id="'. $row->store_id .'" >Update</button>
                    // ';

                    $viewUrl = route('inventories.show', $row->stock_id);
                    $serviceTag = e($row->service_tag ?? 'N/A');
                    $storeId = e($row->store_id ?? 'N/A');
                    $condition = e($row->asset_condition ?? 'N/A');
                    $assetTag = e($row->asset_tag ?? 'N/A');
                    $updateBtn = auth()->user()->can('inventory-edit') ? '<button type="button" class="btn btn-primary btn-sm open-popup"
                         data-id="%s" data-service-tag="%s" data-store-id="%s" data-condition="%s" data-asset-tag="%s" data-assigned-id="%s" title="Update Stock" >
                         <i class="material-icons">update</i>
                     </button>' : "";

                    // Only single combo button (QR + Barcode) if asset_tag exists
                    $barcodeBtn = '';
                    if (!empty($row->asset_tag)) {
                        $barcodeBtn = '<a href="' . route('stock.print.qr.barcode.combo', $row->stock_id) . '" class="btn btn-warning btn-sm" title="Print QR + Barcode Combo" target="_blank"><i class="material-icons">view_module</i></a>';
                    }

                    if ($row->is_assigned == 1) {
                        $assigned_user = e($row->assigned_id);
                    } else {
                        $assigned_user = e('N/A');
                    }

                    return sprintf(
                        '<a href="%s" class="btn btn-info btn-sm"><i class="material-icons">visibility</i></a>' . $updateBtn . ' ' . $barcodeBtn,
                        e($viewUrl),
                        e($row->stock_id),
                        $serviceTag,
                        $storeId,
                        $condition,
                        $assetTag,
                        $assigned_user
                    );
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }

        return view('backend.admin.inventory.index')->with(compact('suppliers', 'types', 'statuses', 'stores', 'employees','departments'));
    }


    function pending_asset_tag(Request $request)
    {
        $types = Producttype::all();
        $statuses = AssetStatus::all();
        $stores = Store::all();

        $type = $request->type;
        $status = $request->status;
        $store = $request->store;
        $assign = $request->assign;




        $query = Stock::query();

        if ($type) {
            $query->where('producttype_id', $type);
        }
        if ($status) {
            $query->where('status_id', $status);
        }
        if ($store) {
            $query->where('store_id', $store);
        }
        if ($assign) {
            $query->where('is_assigned', $assign);
        }

        $inventories = $query->where('asset_tag', null)->get();

        return view("backend.admin.inventory.pending")->with(compact('inventories', 'types', 'statuses', 'stores'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('backend.admin.purchase.create')->with(compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {

        $this->validate($request, array(
            'product' => 'required|integer',
            'supplier' => 'required|integer',
            'unit_price' => 'required',
            'quantity' => 'required|integer',
            'date_of_purchase' => 'required',

        ));

        $total = $request->quantity * $request->unit_price;
        $purchase = new Purchase();

        $purchase->product_id = $request->product;
        $purchase->supplier_id = $request->supplier;
        $purchase->unite_price = $request->unit_price;
        $purchase->quantity = $request->quantity;
        $purchase->total_price = $total;
        $purchase->purchase_date = $request->date_of_purchase;
        $purchase->save(); //   = $request->date_of_purchase;

    session()->flash('toast.success', 'Succesfully Saved');
    return redirect()->route('purchases.index');
    }
    public function show($id)
    {
        $stock = Stock::find($id);
        //dd($stock);
        return view('backend.admin.inventory.show')->with(compact('stock'));
    }

    public function update(Request $request, $id)
    {
        // $rules = [
        //     'asset_tag' => 'required',
        // ];


        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {

        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Asset Tag is required',
        //     ]);
        // }


        // Validate input
        $request->validate([
            'store_id' => 'nullable|integer|max:255',
            'condition' => 'nullable|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'asset_tag' => 'nullable|string|max:255',
        ]);
        // return $request->all();
        try {
            $inventory = Stock::findOrFail($id);

            if ($request->store_id) {
                $inventory->store_id = $request->store_id;
            }

            if ($request->condition) {
                $inventory->asset_condition = $request->condition;
            }
            if ($request->serial_no) {
                $inventory->service_tag = $request->serial_no;
            }
            if ($request->asset_tag) {
                $inventory->asset_tag = $request->asset_tag;
            }

            if ($request->employee_id) {

                if ($inventory->is_assigned == 1) {
                    $user_transection = Transection::where('stock_id', $id)->whereNull('return_date')->first();

                    if ($user_transection->employee_id != $request->employee_id) {
                        $user_transection->return_date = date("Y-m-d");
                        $user_transection->save();
                        Transection::create([
                            'stock_id' => $id,
                            'employee_id' => $request->employee_id,
                            'quantity' => 1,
                            'issued_date' => date("Y-m-d")
                        ]);
                        UserLogHelper::log('create', 'Assign product to User: ' . $inventory->id);
                    }


                } else {
                    Transection::create([
                        'stock_id' => $id,
                        'employee_id' => $request->employee_id,
                        'quantity' => 1,
                        'issued_date' => date("Y-m-d")
                    ]);
                    UserLogHelper::log('create', 'Assign product to User: ' . $inventory->id);
                    $inventory->is_assigned = 1;
                }

            }



            if ($inventory->save()) {

                UserLogHelper::log('update', 'Updated Inventory: ' . $inventory->id);
                return response()->json(['message' => 'Inventory updated successfully'], 201);
            } else {
                return response()->json(['error' => 'Failed to update inventory'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }

    }


    public function update_tag(Request $request, $id)
    {
        $rules = [
            'asset_tag' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json([
                'status' => 400,
                'message' => 'Asset Tag is required',
            ]);
        }

        $inventory = Stock::find($id);
        $inventory->asset_tag = $request->asset_tag;

        if ($inventory->save()) {
            UserLogHelper::log('update', 'Updated Inventory asset_tag: ' . $inventory->id);
            return response()->json([
                'message' => 'Inventory Updated',
                'status' => 200,
            ]);
        } else {
            UserLogHelper::log('update', 'Trying to update asset_tag: ' . $inventory->id);
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 400,
            ]);
        }
    }

    public function uploadBulk(Request $request)
    {
        $request->validate([
            'asset_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Import and update records
        Excel::import(new StockImport, $request->file('asset_file'));

    session()->flash('toast.success', 'Succesfully Imported');
        return redirect()->back();
    }


    public function updateStatus()
    {
        $stockes = Stock::where('is_assigned', 1)->pluck('id', 'service_tag');

        $arr = [];
        foreach ($stockes as $data) {
            $tran = Transection::where('stock_id', $data->id)->exists();
            if (!$tran) {
                $arr[] = $data->service_tag;
            }
        }

        return $arr;
    }
}
