<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Stock;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\View\View;
use App\Models\Producttype;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;



class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $purchases = Purchase::all();
        return view('backend.admin.purchase.index')->with(compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $types = Producttype::all();
        return view('backend.admin.purchase.create')->with(compact('suppliers', 'products', 'types'));
    }

    public function product($id)
    {
        $product = Product::find($id);

        $product = Product::join('producttypes', 'products.producttype_id', '=', 'producttypes.id')
            ->where('products.id', $id)
            ->select('producttypes.slug as type', 'is_license', 'is_serial')
            ->first();

        return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            array(
                'product' => 'required|integer',
                'invoice_no' => 'required',
                'supplier' => 'required|integer',
                'unit_price' => 'required',
                // 'quantity'          => 'required|integer',
                'date_of_purchase' => 'required',
                // 'serials'           => 'required_if:serial,1',
                'month' => 'required_if:license,1',

            )
        );


        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier;
        $purchase->total_price = $request->grand_total;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->challan_no = $request->challan_no;
        $purchase->purchase_date = $request->date_of_purchase;
        $purchase->is_stocked = 2;
        $purchase->save();


        // Purchase Products Add

        $input = $request->all();

        $purchase_date = Carbon::create($request->date_of_purchase);

        for ($i = 0; $i < count($input['product_id']); $i++) {

            $current_product = Product::find($input['product_id'][$i]);

            if ($current_product->is_license == 1) {
                $warranty = (int) $input['month'][$i];
                $expirationDate = $purchase_date->copy()->addDays($warranty);
                $expired_date = $expirationDate->isoFormat('YYYY-MM-DD');
            } else {
                $expired_date = null;
                $warranty = null;
            }

            if ($current_product->is_serial == 1) {

                $serial_new = 'serials-' . $input['product_id'][$i];

                if (!empty($input[$serial_new])) {
                    $product_serial = json_encode($input[$serial_new]);
                } else {
                    $product_serial = $input[$serial_new];
                }
            } else {
                $product_serial = null;
            }

            $purchase_items = new PurchaseProduct();

            $purchase_items->purchase_id = $purchase->id;
            $purchase_items->supplier_id = $purchase->supplier_id;
            $purchase_items->product_id = $input['product_id'][$i];
            $purchase_items->quantity = $input['quantity'][$i];
            $purchase_items->unit_price = $input['unit_price'][$i];
            $purchase_items->total_price = $input['total'][$i];
            $purchase_items->serials = $product_serial;
            $purchase_items->warranty = $warranty;
            $purchase_items->purchase_date = $request->date_of_purchase;
            $purchase_items->expired_date = $expired_date;
            $purchase_items->is_stocked = 2;
            $purchase_items->save();
        } //End For Loop


        Toastr::success('Succesfully Saved ', 'Success');
        return redirect()->route('purchases.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::find($id);
        return view('backend.admin.purchase.show')->with(compact('purchase'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function typedProducts($id)
    {

        // $products = DB::table('stocks')
        //     ->join('products', 'products.id', '=', 'stocks.product_id')
        //     //->join('orders', 'users.id', '=', 'orders.user_id')
        //     ->select('stocks.*', 'products.title')
        //     ->where('stocks.producttype_id', $id)
        //     ->where('product_status', 1)
        //     ->where('is_assigned', 2)
        //     ->get();


        $products = Product::where('producttype_id', $id)->get();

        return $products;

    }


    public function addInventory($id)
    {
        $data = PurchaseProduct::find($id);

        $type_id = $data->product->type->id;

        if (!empty($data->serials)) {
            $new_serials = json_decode($data->serials);
            // return 'empty' ;
        } else {
            $new_serials = $data->serials;
        }

        if ($data->is_stocked == 2) {

            if ($data->product->type->slug == 'laptop') {

                $max_serial = Stock::max('serial_no');

                if (empty($max_serial)) {
                    $max_serial = 0;
                }

                $x = 1;
                // return explode(',', $data->serials);

                while ($x <= $data->quantity) {

                    $stock = new Stock();

                    // $stock->product_id          = $data->product_id;
                    $stock->product_id = $data->product_id;
                    $stock->pproduct_id = $id;
                    $stock->producttype_id = $type_id;
                    $stock->purchase_id = $data->purchase_id;
                    $stock->purchase_date = $data->purchase_date;
                    $stock->expired_date = $data->expired_date;
                    $stock->warranty = $data->warranty;
                    $stock->quantity = 0;
                    $stock->assigned = 0;

                    // if(!empty($new_serials)){
                    //     $stock->service_tag = $new_serials[$x - 1];
                    // }

                    $stock->service_tag = empty($new_serials) ? NULL : $new_serials[$x - 1];
                    $stock->serial_no = NULL; //$max_serial + $x;
                    $stock->product_status = 1;
                    $stock->is_assigned = 2;
                    $stock->save();
                    $x++;

                }

            } else if ($data->product->type->slug == 'software') {

                $stock = new Stock();
                $stock->product_id = $data->product_id;
                $stock->pproduct_id = $id;
                $stock->producttype_id = $type_id;
                $stock->purchase_id = $data->purchase_id;
                $stock->purchase_date = $data->purchase_date;
                $stock->expired_date = $data->expired_date;
                $stock->warranty = $data->warranty;
                $stock->quantity = $data->quantity;
                $stock->service_tag = NULL;
                $stock->product_status = 1;
                $stock->is_assigned = 2;
                $stock->assigned = 0;
                $stock->save();

            } else {
                $x = 1;

                while ($x <= $data->quantity) {



                    $stock = new Stock();
                    $stock->pproduct_id = $id;
                    $stock->product_id = $data->product_id;
                    $stock->producttype_id = $type_id;
                    $stock->purchase_id = $data->purchase_id;
                    $stock->purchase_date = $data->purchase_date;
                    $stock->expired_date = $data->expired_date;
                    $stock->warranty = $data->warranty;
                    $stock->quantity = 0;
                    $stock->assigned = 0;

                    // if(!empty($new_serials)){
                    //     $stock->service_tag = $new_serials[$x - 1];
                    // }

                    $stock->service_tag = empty($new_serials) ? NULL : $new_serials[$x - 1];

                    // $stock->serial_no = $max_serial + $x;

                    $stock->product_status = 1;
                    $stock->is_assigned = 2;
                    $stock->save();
                    $x++;

                }
            }

            $data->is_stocked = 1;
            $data->save();

            Purchase::where('id', $data->purchase_id)->update(['is_stocked' => 1]);

            Toastr::success(' Succesfully Added to Inventory ', 'Success');
            return redirect()->back();

        } else {
            Toastr::error(' Already Added in Inventory ', 'Failed');

            return redirect()->back();
        }

    }

    public function invoice()
    {

        $record = Purchase::latest()->first();
        $today = Carbon::today()->format('jmY');


        if ($record) {
            $expNum = explode('-', $record->invoice_no);
            if ($expNum[0] == $today) {
                $last_no = (int) $expNum[1] + 1;
                $nextInvoiceNumber = $expNum[0] . '-' . sprintf('%03d', $last_no);
            } else {
                $nextInvoiceNumber = Carbon::today()->format('jmY') . '-001';
            }
        } else {
            $nextInvoiceNumber = Carbon::today()->format('jmY') . '-001';
        }

        return $nextInvoiceNumber;

    }

    public function edit($id): View
    {
        $purchase = Purchase::find($id);
        $products = Product::all();
        $suppliers = Supplier::all();
        $types = Producttype::all();
        return view('backend.admin.purchase.edit')->with(compact('purchase', 'suppliers', 'products', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate(
            $request,
            array(
                'product' => 'required|integer',
                'invoice_no' => 'required',
                'supplier' => 'required|integer',
                'unit_price' => 'required',
                // 'quantity'          => 'required|integer',
                'date_of_purchase' => 'required',
                // 'serials'           => 'required_if:serial,1',
                'month' => 'required_if:license,1',

            )
        );



        $purchase = Purchase::find($id);
        $purchase->supplier_id = $request->supplier;
        $purchase->total_price = $request->grand_total;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->challan_no = $request->challan_no;
        $purchase->purchase_date = $request->date_of_purchase;
        $purchase->is_stocked = 2;
        // $purchase->save();


        // Purchase Products Add

        $input = $request->all();

        // return $input['product_id'];

        $purchase_date = (int) $request->date_of_purchase;
        for ($i = 0; $i < count($input['product_id']); $i++) {

            $product_id = $input['product_id'][$i];
            return $product_id;

            $current_product = Product::find($product_id);
            $product_exsits = PurchaseProduct::where("purchase_id", $id)->where("product_id", $product_id)->exists();


            if ($current_product->is_license == 1) {
                $warranty = (int) $input['month'][$i];
                $dt = Carbon::create($purchase_date);
                $dt->addMonth($warranty);
                $dt->subDays();
                $expired_date = $dt->isoFormat('YYYY-MM-DD');
            } else {
                $expired_date = null;
            }


            if (!$product_exsits && $current_product->is_serial == 1) {

                $serial_new = 'serials-' . $product_id;

                if (!empty($input[$serial_new])) {
                    $product_serial = json_encode($input[$serial_new]);
                } else {
                    $product_serial = $input[$serial_new];
                }
            } else {
                $product_serial = null;
            }


            $product_exsits = PurchaseProduct::where("purchase_id", $id)->where("product_id", $product_id)->exists();
            return $product_exsits;

            if ($product_exsits) {
                $purchase_items = PurchaseProduct::find($product_id);
            } else {
                $purchase_items = new PurchaseProduct();
            }



            $purchase_items->purchase_id = $purchase->id;
            $purchase_items->supplier_id = $purchase->supplier_id;
            $purchase_items->product_id = $input['product_id'][$i];
            $purchase_items->quantity = $input['quantity'][$i];
            $purchase_items->unit_price = $input['unit_price'][$i];
            $purchase_items->total_price = $input['total'][$i];
            $purchase_items->serials = $product_serial;
            $purchase_items->warranty = $warranty;
            $purchase_items->purchase_date = $request->date_of_purchase;
            $purchase_items->expired_date = $expired_date;
            $purchase_items->is_stocked = 2;

            $purchase_items->save();
        } //End For Loop


        Toastr::success('Succesfully Updated ', 'Success');
        return redirect()->route('purchases.index');
    }

    public function purchasedProducts()
    {
        $products = PurchaseProduct::orderBy('is_stocked', 'DESC')->get();
        return view('backend.admin.purchase.purchased_products')->with(compact('products'));
    }

    public function purchasedProductShow($id)
    {
        $product = PurchaseProduct::findOrFail($id);
        return view('backend.admin.purchase.purchased_show')->with(compact('product'));
    }



    public function grn($id)
    {

        $purchase = Purchase::findOrFail($id);

        // return view('backend.admin.pdf.grn', compact('purchase'));
        $pdf = Pdf::loadView('backend.admin.pdf.grn', compact('purchase'))->setPaper('a4', 'landscape');

        return $pdf->stream('grn-' . $purchase->invoice_no . '.pdf');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
