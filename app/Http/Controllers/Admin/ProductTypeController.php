<?php

namespace App\Http\Controllers\Admin;

use App\Models\Producttype;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ProductTypeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:product-type-list|product-type-create|product-type-edit|product-type-delete', ['only' => ['index','store']]);
         $this->middleware('permission:product-type-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-type-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-type-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
         $types = Producttype::latest()->get();
         return view('backend.admin.product-type')->with(compact('types'));
    }
    public function store(Request $request)
    {
        $this->validate($request,array(
            'name' => 'required|max:255|unique:producttypes'
        ));
        //$slug  = str_slug($request->name);
        $type = new Producttype();
        $type->name = $request->name;
        $type->slug = Str::slug($request->name);
        $type->save();
        UserLogHelper::log('create', 'Created a new Product Type : '. $type->id );
        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }


    public function edit($id)
    {
        $type = Producttype::find($id);
        return $type;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,array(
            'name' => 'required|max:255|unique:producttypes'
        ));

        //$slug  = str_slug($request->name);
        $type = Producttype::find($id);
        $type->name = $request->name;
        $type->slug = Str::slug($request->name);
        $type->save();

        UserLogHelper::log('update', 'updated a  Product Type : '. $type->id );

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $type = Producttype::find($id);
        $type->delete();

        UserLogHelper::log('delete', 'Deleted a  Product Type : '. $type->id );

        Toastr::success('Succesfully Deleted ', 'Success');

        return redirect()->back();
    }
}
