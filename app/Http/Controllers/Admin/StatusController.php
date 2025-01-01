<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetStatus;
use App\Models\Stock;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Str;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = AssetStatus::all();
        return view('backend.admin.statuses')->with(compact('statuses'));
    }
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:255',
        ));

        $slug = Str::slug($request->name);
        $status = new AssetStatus();
        $status->name = $request->name;
        $status->slug = $slug;
        $status->save();

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $status = AssetStatus::find($id);
        return $status;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|max:255',
        ));

        $slug = Str::slug($request->name);
        $status = AssetStatus::find($id);
        $status->name = $request->name;
        $status->slug = $slug;
        $status->save();
        $status->save();
        Toastr::success(' Succesfully Updated ', 'Success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $status = AssetStatus::find($id);

        $stock = Stock::where('status_id', '=', $id)->exists();


        if ($stock) {

            Toastr::error('Delete Resticted  ', 'Error');
        } else {
            $status->delete();
            Toastr::success('Succesfully Deleted  ', 'Success');
        }

        return redirect()->back();


    }
}
