<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Employee;

use App\Models\Producttype;
use App\Models\Transection;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class TransectionController extends Controller
{
     function __construct()
    {
        $this->middleware('permission:distribution-list|distribution-create|distribution-edit|distribution-delete', ['only' => ['index', 'store', 'return', 'typedProducts', 'singleStock', 'multiAck', 'return']]);
        $this->middleware('permission:distribution-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:distribution-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:distribution-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $transections = Transection::all();
        return view('backend.admin.transection.index')->with(compact('transections'));
    }

    public function create()
    {
        // $stoks = Stock::where('product_status', 1)->where('is_assigned', 2)->get();
        $types = Producttype::all();
        $employees = Employee::all();
        // return $types;
        return view('backend.admin.transection.create')->with(compact('employees', 'types'));
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            'product_type' => 'required|integer',
            'product' => 'required|integer',
            'employee' => 'required|integer',
            'quantity' => 'required|integer',
            'date_of_issue' => 'required',

        ));

        $is_approved = 0;

        $type = Producttype::find($request->product_type);
        $stock = Stock::find($request->product);

        if ($type->slug == 'software') {

            $remain = $stock->quantity - $stock->assigned;

            if ($remain >= $request->quantity) {
                $is_approved = 1;
                $stock->assigned = $stock->assigned + $request->quantity;
            } else {
                $is_approved = 0;
            }
        } else {
            $is_approved = 1;
        }

        if ($is_approved == 1) {

            $transection = new Transection();
            $transection->stock_id = $stock->id;
            $transection->employee_id = $request->employee;
            $transection->quantity = $request->quantity;
            $transection->issued_date = $request->date_of_issue;

            // $transection->mouse          = $request->mouse;
            // $transection->pendrive       = $request->pendrive;
            // $transection->bag            = $request->laptop_bag;

            $transection->comment = $request->comment;
            $transection->save();

            $stock->is_assigned = 1;
            $stock->save();

            UserLogHelper::log('create', 'Assigned a Product To Employee : '. $transection->id );

            // if($type->slug == 'software'){
            //     Stock::where('id',$request->product)->update(['assigned'=> $current_stock]);
            // }else {
            //     Stock::where('id',$request->product)->update(['is_assigned'=> 1]);
            // }


            if ($request->print_ack == 1) {
                Toastr::success(' Succesfully Saved ', 'Success');
                return redirect()->route('transections.ack', $transection->id);
            }

            Toastr::success(' Succesfully Saved ', 'Success');
            return redirect()->route('transections.index');

        } else {
            Toastr::error('No license is available for assign', 'Error');
            return redirect()->back()->withInput();
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'date_of_return' => 'required',
        ));

        // return $request->all();

        // Transection::where('id',$id)->update(['return_date'=> ]);

        $transection = Transection::find($id);

        $transection->return_date = $request->date_of_return;
        $transection->save();

        if ($transection->stock->producttype->slug == 'software') {
            $stock_get = Stock::find($transection->stock_id);
            $stock_get->assigned = $stock_get->assigned - $transection->quantity;
            $stock_get->save();
            if ($stock_get->assigned == 0) {
                Stock::where('id', $transection->stock_id)->update(['is_assigned' => 2]);
            }

        } else {
            Stock::where('id', $transection->stock_id)->update(['is_assigned' => 2]);
        }

        UserLogHelper::log('update', 'Updated Stock Return from Employee : '. $transection->id );

        Toastr::success(' Succesfully Updated ', 'Success');
        return redirect()->back();


    }

    public function show($id)
    {
        $transection = Transection::find($id);
        return view('backend.admin.transection.show')->with(compact('transection'));
    }


    public function typedProducts($id)
    {

        $type = Producttype::find($id);

        $products = DB::table('stocks')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->join('producttypes', 'producttypes.id', '=', 'stocks.producttype_id')
            //->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('stocks.id', 'stocks.serial_no', 'stocks.service_tag', 'stocks.quantity', 'products.title', 'products.brand', 'products.model', 'producttypes.slug')
            ->where('stocks.producttype_id', $id);
        //->where('product_status', 1);

        if ($type->slug == 'software') {
            $products->where('quantity', '>', 0);
        } else {
            $products->where('is_assigned', 2);
        }

        // ->get();

        return response()->json([
            'products' => $products->get(),
            'type' => $type->slug,
        ]);

    }

    public function singleStock($id)
    {
        $stock = Stock::find($id);
        return $stock;
    }

    public function ack($id)
    {
        $transection = Transection::findOrFail($id);

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.ack', compact('transection'))->setPaper('a4');

        return $pdf->stream('grn-' . $transection->date . '.pdf');


    }


    public function multiAck(Request $request)
    {

        // return $request->all();
        $transections = Transection::whereIn('id', $request->print_ack)->get();
        $employee = Employee::find($request->emply_id);
        $isdate = $request->issued_date;

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.ac-multiple', compact('transections', 'employee', 'isdate'))->setPaper('a4');
        // return $pdf;

        return $pdf->stream('ack-' . $employee->id . '.pdf');


    }

    public function return($id)
    {
        $transection = Transection::findOrFail($id);

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.return', compact('transection'))->setPaper('a4');

        return $pdf->stream('return-' . $transection->employee->emply_id . '-' . $transection->return_date . '.pdf');


    }

}
