<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Producttype;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Models\PurchaseProduct;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $types = Producttype::all();
        $products = Product::all();
        return view('backend.admin.products')->with(compact('products', 'types'));
    }
    public function store(Request $request)
    {
        $this->validate($request, array(
            // 'title' => 'required|max:255',
            'brand' => 'required|max:255',
            'type' => 'required|numeric',
            'model' => 'required|max:255',
            'unit' => 'required|alpha|max:255',
            'serial' => 'sometimes|numeric|max:1',
            'license' => 'sometimes|numeric|max:1',
            'taggable' => 'sometimes|numeric|max:1',
            'description' => 'required',
        ));

        $ptype = Producttype::find($request->type);
        $title = $request->brand . " " . $request->model . " " . $ptype->name;

        $product = new Product();
        $product->title = $title;
        $product->producttype_id = $request->type;
        $product->model = $request->model;
        $product->brand = $request->brand;
        $product->unit = $request->unit;
        $product->is_license = $request->license ?? 2;
        $product->is_serial = $request->serial ?? 2;
        $product->is_taggable = $request->taggable ?? 2;
        $product->is_consumable = $request->is_consumable ?? 2;
        $product->description = $request->description;
        // $product->description       = $request->title." ". $request->brand." ". $request->model." ".$request->description;
        $product->slug = Str::slug($title);
        $product->save();

        UserLogHelper::log('create', 'Created a new Product : '. $product->id );

        if ($request->ajax()) {
            return response()->json([
                'id' => $product->id,
                'title' => $product->title,
                'is_serial' => $product->is_serial,
                'is_license' => $product->is_license,
                'is_taggable' => $product->is_taggable,
                'is_consumable' => $product->is_consumable,
            ], 201);
        }

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'brand' => 'required|max:255',
            'type' => 'required|numeric',
            'model' => 'required|max:255',
            'unit' => 'required|alpha|max:255',
            'serial' => 'sometimes|numeric|max:1',
            'license' => 'sometimes|numeric|max:1',
            'taggable' => 'sometimes|numeric|max:1',
            'description' => 'required|max:255',
        ));


        $products = false;//Purchase::where('product_id', '=', $id )->exists();

        if ($products) {
            Toastr::error(' Update Restricted ', 'Error');
        } else {
            //$slug  = str_slug($request->name);

            $ptype = Producttype::find($request->type);
            $title = $request->brand . " " . $request->model . " " . $ptype->name;

            $product = Product::find($id);
            $product->title = $title;
            $product->producttype_id = $request->type;
            $product->model = $request->model;
            $product->brand = $request->brand;
            $product->unit = $request->unit;
            $product->is_license = $request->license ?? 2;
            $product->is_serial = $request->serial ?? 2;
            $product->is_taggable = $request->taggable ?? 2;
            $product->is_consumable = $request->has('is_consumable') ? 1 : 0;
            $product->description = $request->description;
            $product->save();

            UserLogHelper::log('update', 'Updated Product : '. $product->id );

            Toastr::success(' Succesfully Updated ', 'Success');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        $products = PurchaseProduct::where('product_id', '=', $id)->exists();

        if ($products) {
            Toastr::error(' Delete Restricted ', 'Error');
        } else {
            $product->delete();
            UserLogHelper::log('delete', 'Deleted a Product : '. $product->title );
            Toastr::success('Succesfully Deleted ', 'Success');
        }

        return redirect()->back();
    }
}

