<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetStatus;
use App\Models\Producttype;
use App\Models\Store;
use App\Models\Transection;
use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Stock;
use Toastr;
use Validator;

class InventoryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:inventory-list|inventory-create|inventory-edit|inventory-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:inventory-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:inventory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:inventory-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        // $inventories = Stock::all();
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

        $inventories = $query->get();


        return view('backend.admin.inventory.index')->with(compact('inventories', 'types', 'statuses', 'stores'));
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
        $purchase->save();//   = $request->date_of_purchase;

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
        // $inventory->mac = $request->mac;
        $inventory->asset_tag = $request->asset_tag;
        $inventory->save();

        return response()->json([
            'message' => 'Inventory Updated',
            'status' => 200,
        ]);
    }


    public function updateStatus(){
        $stockes = Stock::where('is_assigned', 1)->get();

        $arr = [];
        foreach($stockes as $data){
            $tran = Transection::where('stock_id', $data->id)->exists();
            if(!$tran){
                $arr[] = $data->service_tag;
            }
        }

        return $arr;


    }


}
