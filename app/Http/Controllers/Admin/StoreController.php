<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Http\Request;
use Str;
use Brian2694\Toastr\Facades\Toastr;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('backend.admin.stores')->with(compact('stores'));
    }
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ));

        $slug = Str::slug($request->name);
        $store = new Store();
        $store->name = $request->name;
        $store->address = $request->address;
        $store->slug = $slug;
        $store->save();

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $store = Store::find($id);
        return $store;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ));

        $store = Store::find($id);
        $slug = Str::slug($request->name);
        $store->name = $request->name;
        $store->address = $request->address;
        $store->slug = $slug;
        $store->save();
        Toastr::success(' Succesfully Updated ', 'Success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $store = Store::find($id);

        $stock = Stock::where('store_id', '=', $id)->exists();


        if ($stock) {

            Toastr::error('Delete Resticted  ', 'Error');
        } else {
            $store->delete();
            Toastr::success('Succesfully Deleted  ', 'Success');
        }

        return redirect()->back();


    }
}