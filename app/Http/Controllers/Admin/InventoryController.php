<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\AssetStatus;

use App\Models\Producttype;
use App\Models\Transection;
use App\Imports\StockImport;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
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
        $employees = Employee::where('status', 1)->get();

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

        $inventories = $query->get();


        return view('backend.admin.inventory.index')->with(compact('inventories', 'types', 'statuses', 'stores', 'employees'));
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

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->route('admin.purchases.index');
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




        $inventory = Stock::find($id);

        if ($request->field == 'condition') {
            $inventory->condition = $request->value;
        }
        if ($request->field == 'store_id') {
            $inventory->store_id = $request->value;
        }


        if ($request->field == 'employee_id') {

            $transection = new Transection();
            $transection->stock_id = $id;
            $transection->employee_id = $request->value;
            $transection->quantity = 1;
            $transection->issued_date = date("Y-m-d");
            $transection->save();

            $inventory->is_assigned = 1;

            UserLogHelper::log('create', 'Assign product to User: ' . $inventory->id);

            if ($inventory->save()) {
                return response()->json([
                    'message' => 'The Product has been assigned',
                    'status' => 200,
                ]);
            }
        }



        if ($inventory->save()) {
            UserLogHelper::log('update', 'Updated Inventory: ' . $inventory->id);
            return response()->json([
                'message' => 'Inventory Updated',
                'status' => 200,
            ]);
        } else {
            UserLogHelper::log('update', 'Trying to update: ' . $inventory->id);
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 400,
            ]);
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

        Toastr::success('Succesfully Imported ', 'Success');
        return redirect()->back();
    }


    public function updateStatus()
    {
        $stockes = Stock::where('is_assigned', 1)->get();

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
