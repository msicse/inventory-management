<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Product;
use App\Models\Producttype;
use App\Models\Requisition;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{

    function index()
    {
        $requisitions = Requisition::all();
        return view("backend.admin.requisition.index", compact("requisitions"));
    }
    function create()
    {
        $types = Producttype::all();
        $products = Product::all();
        $departments = Department::all();


        return view("backend.admin.requisition.create", compact("types", "products", "departments"));
    }
    function store(Request $request)
    {
    //    return $request->all();

        $this->validate($request, array(
            'department_id' => 'required|integer',
            'product_type' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required',
            'justification' => 'required',
        ));



        $requisition = new Requisition();
        $requisition->producttype_id = $request->product_type;
        $requisition->product_id = $request->product > 0 ? $request->product : null;
        $requisition->department_id = $request->department_id;
        $requisition->quantity = $request->quantity;
        $requisition->description = $request->description;
        $requisition->justification = $request->justification;
        $requisition->remarks = $request->remarks;
        $requisition->status = "pending";
        $requisition->save();

        Toastr::success('Succesfully Saved', 'Success');
        return redirect()->route('requisitions.index');

    }

    function show($id) {

    }



}
