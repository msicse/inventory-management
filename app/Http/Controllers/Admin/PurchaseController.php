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
use App\Helpers\UserLogHelper;
use App\Models\PurchaseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['index', 'store', 'grn', 'purchasedProductShow', 'purchasedProducts', 'invoice', 'addInventory', 'typedProducts', 'generateBarcode', 'printBarcode', 'printMultipleBarcodes']]);
        $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
        $this->middleware('permission:purchase-addinventory', ['only' => ['addInventory']]);
    }
    public function index(Request $request)
    {
        $query = Purchase::query();

        // Filter by approved status (is_stocked)
        if ($request->has('approved') && $request->approved !== '') {
            $query->where('is_stocked', $request->approved);
        }

        $purchases = $query->orderBy('created_at', 'desc')->get();

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

        UserLogHelper::log('create', 'Created a new Purchase : '. $purchase->id );


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

                    // Auto-assign asset tag if product is taggable
                    if ($data->product->is_taggable == 1) {
                        $stock->asset_tag = $this->generateAssetTag($data->product->type->name);
                    }

                    $stock->serial_no = NULL; //$max_serial + $x;
                    $stock->status_id = 1;
                    $stock->store_id = 1;
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

                // Auto-assign asset tag if product is taggable
                if ($data->product->is_taggable == 1) {
                    $stock->asset_tag = $this->generateAssetTag($data->product->type->name);
                }

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

                    // Auto-assign asset tag if product is taggable
                    if ($data->product->is_taggable == 1) {
                        $stock->asset_tag = $this->generateAssetTag($data->product->type->name);
                    }

                    // $stock->serial_no = $max_serial + $x;

                    $stock->status_id  = 1;
                    $stock->store_id  = 1;
                    $stock->is_assigned = 2;
                    $stock->asset_condition = 'good';
                    $stock->save();
                    $x++;

                }
            }

            $data->is_stocked = 1;
            $data->save();

            Purchase::where('id', $data->purchase_id)->update(['is_stocked' => 1]);

            UserLogHelper::log('create', 'Added Purchase to Inventory PurchaseProduct ID : '. $id );

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
                'product_id' => 'required|array',
                'product_id.*' => 'required|integer',
                'invoice_no' => 'required',
                'supplier' => 'required|integer',
                'unit_price' => 'required|array',
                'unit_price.*' => 'required|numeric',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer',
                'date_of_purchase' => 'required',
                'month' => 'required|array',
                'month.*' => 'required_if:license,1',
            )
        );



        $purchase = Purchase::find($id);
        $purchase->supplier_id = $request->supplier;
        $purchase->total_price = $request->grand_total;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->challan_no = $request->challan_no;
        $purchase->purchase_date = $request->date_of_purchase;
        $purchase->is_stocked = 2;
        $purchase->save();

        // Get current products in this purchase
        $existingProducts = PurchaseProduct::where('purchase_id', $id)->get();
        $submittedProductIds = $request->product_id ?? [];

        // Remove products that are no longer in the form
        foreach ($existingProducts as $existingProduct) {
            if (!in_array($existingProduct->product_id, $submittedProductIds)) {
                $existingProduct->delete();
            }
        }

        // Process submitted products (update existing or create new)
        $input = $request->all();

        for ($i = 0; $i < count($submittedProductIds); $i++) {
            $product_id = $submittedProductIds[$i];
            $current_product = Product::find($product_id);

            // Handle warranty/expiry date
            $warranty = null;
            $expired_date = null;
            if ($current_product->is_license == 1) {
                $warranty = (int) ($input['month'][$i] ?? 0);
                if ($warranty > 0) {
                    $purchase_date = Carbon::create($request->date_of_purchase);
                    $expired_date = $purchase_date->copy()->addMonths($warranty)->format('Y-m-d');
                }
            }

            // Handle serials
            $product_serial = null;
            if ($current_product->is_serial == 1) {
                $serial_field = 'serials-' . $product_id;
                if (!empty($input[$serial_field])) {
                    $product_serial = json_encode($input[$serial_field]);
                }
            }

            // Find existing product or create new
            $purchase_item = PurchaseProduct::where('purchase_id', $id)
                                           ->where('product_id', $product_id)
                                           ->first();

            if (!$purchase_item) {
                $purchase_item = new PurchaseProduct();
            }

            // Update/set product data
            $purchase_item->purchase_id = $purchase->id;
            $purchase_item->supplier_id = $purchase->supplier_id;
            $purchase_item->product_id = $product_id;
            $purchase_item->quantity = $input['quantity'][$i];
            $purchase_item->unit_price = $input['unit_price'][$i];
            $purchase_item->total_price = $input['total'][$i];
            $purchase_item->serials = $product_serial;
            $purchase_item->warranty = $warranty;
            $purchase_item->purchase_date = $request->date_of_purchase;
            $purchase_item->expired_date = $expired_date;
            $purchase_item->is_stocked = 2;

            $purchase_item->save();
        }


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
        UserLogHelper::log('create', 'Created a new GRN : '. $id );

        return $pdf->stream('grn-' . $purchase->invoice_no . '.pdf');

    }

    /**
     * Generate barcode for a stock item
     *
     * @param  int  $stockId
     * @return \Illuminate\Http\Response
     */
    public function generateBarcode($id)
    {
        try {
            // Step 1: Basic data retrieval without relationships
            $stock = Stock::findOrFail($id);

            // Step 2: Create simple barcode data
            $barcodeData = $this->createBarcodeData($stock);

            // Step 3: Try barcode generation using the facade
            // Simple barcode settings for testing
            $barcodeHTML = DNS1D::getBarcodeHTML($barcodeData, 'C128', 1, 50, 'black', false);

            return response('<div style="text-align: center; padding: 20px;">
                <h3>Test Barcode Generation</h3>
                <div>' . $barcodeHTML . '</div>
                <p>Data: ' . $barcodeData . '</p>
                <p>Stock ID: ' . $stock->id . '</p>
            </div>');

        } catch (\Exception $e) {
            return response('<div style="color: red; padding: 20px;">
                <h3>Error occurred:</h3>
                <p>' . $e->getMessage() . '</p>
                <pre>' . $e->getTraceAsString() . '</pre>
            </div>', 500);
        }
    }    /**
     * Generate and print barcode label for a stock item
     *
     * @param  int  $stockId
     * @return \Illuminate\Http\Response
     */
    public function printBarcode($stockId)
    {
        // Simple stock loading without relationships
        $stock = Stock::findOrFail($stockId);

        // Create simple barcode data
        $barcodeData = $this->createBarcodeData($stock);

        try {
            // Simple HTML barcode generation without file operations
            $dns1d = new \Milon\Barcode\DNS1D();
            // Simple barcode settings
            $barcodeHTML = $dns1d->getBarcodeHTML($barcodeData, 'C128', 1, 50, 'black', false);

            $data = [
                'stock' => $stock,
                'barcodeHTML' => $barcodeHTML,
                'barcodeData' => $barcodeData,
                'assetTag' => $stock->asset_tag
            ];

            // Custom paper size: 3.5" width x 1.4" height (252pt x 100.8pt) with margins
            $pdf = Pdf::loadView('backend.admin.pdf.barcode-html', $data)
                      ->setPaper([0, 0, 252, 100.8], 'portrait')
                      ->setOptions([
                          'isHtml5ParserEnabled' => true,
                          'isRemoteEnabled' => false,
                          'chroot' => public_path(),
                      ]);

            UserLogHelper::log('create', 'Generated barcode for Stock ID: ' . $stockId);

            return $pdf->stream('barcode-' . $stock->asset_tag . '.pdf');

        } catch (\Exception $e) {
            return response('Barcode generation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate barcodes for multiple stock items
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printMultipleBarcodes(Request $request)
    {
        $stockIds = $request->input('stock_ids', []);

        if (empty($stockIds)) {
            return response('No items selected', 400);
        }

        // Simple stock loading without relationships
        $stocks = Stock::whereIn('id', $stockIds)->get();
        $barcodeData = [];

        foreach ($stocks as $stock) {
            $data = $this->createBarcodeData($stock);
            try {
                // Simple barcode generation without file operations
                $dns1d = new \Milon\Barcode\DNS1D();
                // Simple barcode settings
                $barcode = $dns1d->getBarcodePNG($data, 'C128', 1, 50);

                $barcodeData[] = [
                    'stock' => $stock,
                    'barcode' => 'data:image/png;base64,' . base64_encode($barcode),
                    'barcodeText' => $data,
                    'assetTag' => $stock->asset_tag
                ];
            } catch (\Exception $e) {
                // Skip this item if barcode generation fails
                continue;
            }
        }

        $pdf = Pdf::loadView('backend.admin.pdf.multiple-barcodes', compact('barcodeData'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'dpi' => 300,
                      'defaultFont' => 'Arial',
                      'isRemoteEnabled' => true
                  ]);

        UserLogHelper::log('create', 'Generated multiple barcodes for ' . count($stockIds) . ' items');

        return $pdf->stream('barcodes-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    /**
     * Create barcode data string
     *
     * @param  \App\Models\Stock  $stock
     * @return string
     */
    private function createBarcodeData($stock)
    {
        try {
            // Use only basic stock data to avoid relationship issues
            $serial = $stock->service_tag ?? 'N/A';
            $assetTag = $stock->asset_tag ?? 'N/A';

            // Original barcode data format
            $barcodeData = "RMG Sustainability Council|S/N:{$serial}|Asset:{$assetTag}|URL:its.rsc-bd.org";

            return $barcodeData;

        } catch (\Exception $e) {
            // Simple fallback
            return "RMG|Asset:" . ($stock->asset_tag ?? 'UNKNOWN') . "|URL:its.rsc-bd.org";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Implementation for deleting purchase records if needed
        return response('Delete functionality not implemented yet', 501);
    }

    /**
     * Generate the next asset tag based on product type
     *
     * @param  string  $typeName
     * @return string
     */
    private function generateAssetTag($typeName)
    {
        // Get the first letter of the product type name in uppercase
        $prefix = strtoupper(substr($typeName, 0, 1));

        // Find the highest existing asset tag for this prefix
        $latestAssetTag = Stock::where('asset_tag', 'like', $prefix . '%')
                               ->orderByRaw('CAST(SUBSTRING(asset_tag, 2) AS UNSIGNED) DESC')
                               ->first();

        if ($latestAssetTag && $latestAssetTag->asset_tag) {
            // Extract the numeric part and increment it
            $numericPart = (int) substr($latestAssetTag->asset_tag, 1);
            $nextNumber = $numericPart + 1;
        } else {
            // Start with 1 if no existing asset tags found
            $nextNumber = 1;
        }

        // Format as prefix + 5-digit zero-padded number (e.g., L00001, M00002)
        return $prefix . sprintf('%05d', $nextNumber);
    }

    //Update serial
    function updateAssetTag() {
        $updates = [
            'PF49XZNS' => ['asset_tag' => 'LP00511'],
            'PF49XZF6' => ['asset_tag' => 'LP00527'],
            'PF49P2T0' => ['asset_tag' => 'LP00525'],
            'PF49PC0P' => ['asset_tag' => 'LP00533'],
            'PF49X1R2' => ['asset_tag' => 'LP00501'],
            'PF49XZE9' => ['asset_tag' => 'LP00539'],
            'PF49DSCN' => ['asset_tag' => 'LP00548'],
            'PF49WFYH' => ['asset_tag' => 'LP00529'],
            'PF49X3VB' => ['asset_tag' => 'LP00528'],
            'PF49X1Q7' => ['asset_tag' => 'LP00514'],
            'PF49PAD0' => ['asset_tag' => 'LP00518'],
            'PF49DSBS' => ['asset_tag' => 'LP00507'],
            'PF49P6QS' => ['asset_tag' => 'LP00524'],
            'PF49XQCR' => ['asset_tag' => 'LP00513'],
            'PF49WZPQ' => ['asset_tag' => 'LP00516'],
            'PF49DVK9' => ['asset_tag' => 'LP00520'],
            'PF49XZ3T' => ['asset_tag' => 'LP00509'],
            'PF49DSAS' => ['asset_tag' => 'LP00523'],
            'PF49XNBZ' => ['asset_tag' => 'LP00531'],
            'PF49WFFJ' => ['asset_tag' => 'LP00512'],
            'PF49PADF' => ['asset_tag' => 'LP00519'],
            'PF49D22K' => ['asset_tag' => 'LP00534'],
            'PF49DSDP' => ['asset_tag' => 'LP00543'],
            'PF49XZQR' => ['asset_tag' => 'LP00505'],
            'PF49P4TJ' => ['asset_tag' => 'LP00536'],
            'PF49Y1N2' => ['asset_tag' => 'LP00526'],
            'PF49XQBV' => ['asset_tag' => 'LP00538'],
            'PF49X3TJ' => ['asset_tag' => 'LP00508'],
            'PF49P2TG' => ['asset_tag' => 'LP00549'],
            'PF49P4PT' => ['asset_tag' => 'LP00510'],
            'PF49PABA' => ['asset_tag' => 'LP00550'],
            'PF49XQC7' => ['asset_tag' => 'LP00521'],
            'PF49XZMW' => ['asset_tag' => 'LP00545'],
            'PF49DSE0' => ['asset_tag' => 'LP00541'],
            'PF49X1SB' => ['asset_tag' => 'LP00530'],
            'PF49Y1FC' => ['asset_tag' => 'LP00504'],
            'PF49XQ9H' => ['asset_tag' => 'LP00544'],
            'PF49PAET' => ['asset_tag' => 'LP00515'],
            'PF49XQ95' => ['asset_tag' => 'LP00537'],
            'PF49D213' => ['asset_tag' => 'LP00457'],
            'PF49DVKS' => ['asset_tag' => 'LP00546'],
            'PF49D225' => ['asset_tag' => 'LP00506'],
            'PF49DSEK' => ['asset_tag' => 'LP00542'],
            'PF49XQAS' => ['asset_tag' => 'LP00502'],
            'PF49XZ5H' => ['asset_tag' => 'LP00535'],
            'PF49XZ60' => ['asset_tag' => 'LP00503'],
            'PF49X3R7' => ['asset_tag' => 'LP00532'],
            'PF49XZ3B' => ['asset_tag' => 'LP00517'],
            'PF49D21J' => ['asset_tag' => 'LP00540'],
            'PF49XNCL' => ['asset_tag' => 'LP00522'],
            '5CD411H93R' => ['asset_tag' => 'LP00551'],
            '5CD411H93F' => ['asset_tag' => 'LP00552'],
            '5CD411H926' => ['asset_tag' => 'LP00553'],
            '5CD411H95Q' => ['asset_tag' => 'LP00554'],
            '5CD411H95B' => ['asset_tag' => 'LP00555'],
            '5CD411H9DK' => ['asset_tag' => 'LP00556'],
            '5CD411H9FH' => ['asset_tag' => 'LP00557'],
            '5CD411H9FL' => ['asset_tag' => 'LP00558'],
            '5CD411H9G0' => ['asset_tag' => 'LP00560'],
            '5CD411H9FY' => ['asset_tag' => 'LP00559'],
            '5CD411H9CH' => ['asset_tag' => 'LP00661'],
            '5CD411H950' => ['asset_tag' => 'LP00662'],
            '5CD411H9F1' => ['asset_tag' => 'LP00663'],
            '5CD411H9F4' => ['asset_tag' => 'LP00664'],
            '5CD411H9C1' => ['asset_tag' => 'LP00665'],
            '5CD411H9DD' => ['asset_tag' => 'LP00666'],
            '5CD410BQGL' => ['asset_tag' => 'LP00667'],
            '5CD411H989' => ['asset_tag' => 'LP00668'],
            '5CD411H9B5' => ['asset_tag' => 'LP00669']
        ];


        foreach ($updates as $serialNumber => $data) {
            Stock::where('service_tag', $serialNumber)
            ->update($data);
        }
    }

    /**
     * Test method to isolate barcode generation issues
     */
    public function testSimpleBarcode($id)
    {
        try {
            // Test without any barcode generation library first
            $stock = Stock::findOrFail($id);

            return response('<div style="text-align: center; padding: 20px;">
                <h3>Simple Test - No Barcode Generation</h3>
                <p>Stock ID: ' . $stock->id . '</p>
                <p>Asset Tag: ' . ($stock->asset_tag ?? 'N/A') . '</p>
                <p>Test successful - no infinite loop</p>
            </div>');

        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}
