<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SupplierController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:suppliers-list|suppliers-create|suppliers-edit|suppliers-delete', ['only' => ['index']]);
        $this->middleware('permission:suppliers-create', ['only' => ['store']]);
        $this->middleware('permission:suppliers-edit', ['only' => ['update']]);
        $this->middleware('permission:suppliers-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
         $suppliers = Supplier::all();
         return view('backend.admin.suppliers')->with(compact('suppliers'));
    }
    public function store(Request $request)
    {
        $this->validate($request,array(
            'company'       => 'required|max:255',
            'name'          => 'required|max:255',
            'phone'         => 'required',
            'email'         => 'email',
            'address'       => 'required',
            'description'   => '',
        ));
        //$slug  = str_slug($request->name);
        $supplier = new Supplier();
        $supplier->company      = $request->company;
        $supplier->name         = $request->name;
        $supplier->phone        = $request->phone;
        $supplier->email        = $request->email;
        $supplier->address      = $request->address;
        $supplier->description  = $request->description;
        $supplier->save();
        UserLogHelper::log('create', 'Created a new Supplier : '. $supplier->company );

        // If AJAX request, return JSON for inline creation
        if ($request->ajax()) {
            return response()->json([
                'id' => $supplier->id,
                'company' => $supplier->company,
                'name' => $supplier->name,
            ], 201);
        }

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return $supplier;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,array(
            'company'       => 'required|max:255',
            'name'          => 'required|max:255',
            'phone'         => 'required',
            'email'         => 'email',
            'address'       => 'required',
            'description'   => '',
        ));
        //$slug  = str_slug($request->name);

        $purchases = false;//Purchase::where('supplier_id', '=', $id )->exists();

        if ($purchases) {
            Toastr::error(' Update Restricted ', 'Error');
        } else {
            $supplier = Supplier::find($id);
            $supplier->company      = $request->company;
            $supplier->name         = $request->name;
            $supplier->phone        = $request->phone;
            $supplier->email        = $request->email;
            $supplier->address      = $request->address;
            $supplier->description  = $request->description;
            $supplier->save();
            UserLogHelper::log('update', 'Updated Supplier : '. $supplier->company );
            Toastr::success(' Successfully Updated ', 'Success');
        }


        return redirect()->back();
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);


        $purchases = Purchase::where('supplier_id', '=', $id )->exists();

        if ($purchases) {
            Toastr::error(' Delete Restricted ', 'Error');
        } else {
            UserLogHelper::log('delete', 'Deleted Supplier : '. $supplier->company );
            $supplier->delete();
            Toastr::success('Succesfully Deleted ', 'Success');
        }

        return redirect()->back();
    }
}
