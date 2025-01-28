<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\AssetStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

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

        UserLogHelper::log('create', 'Created a new Status: '. $status->name );

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

        UserLogHelper::log('update', 'Updated Status : '. $status->name );

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

            UserLogHelper::log('delete', 'Deleted Status : '. $status->name );
            $status->delete();
            Toastr::success('Succesfully Deleted  ', 'Success');
        }

        return redirect()->back();


    }
}
