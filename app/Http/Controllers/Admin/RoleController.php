<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class RoleController extends Controller
{
    public function index()
    {
         $roles = Role::latest()->get();
         return view('backend.admin.roles')->with(compact('roles'));
    }
    public function store(Request $request)
    {
        $this->validate($request,array(
            'name' => 'required|max:255|unique:roles'
        ));
        //$slug  = str_slug($request->name);
        $role = new Role();
        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->save();

        UserLogHelper::log('create', 'Created a new Role : '. $role->name );

        Toastr::success('Role Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        $users = User::where('role_id', '=', $id )->exists();
        if ($users) {
            Toastr::error(' Delete Restricted ', 'Error');
        } else {
            UserLogHelper::log('delete', 'Deleted Role : '. $role->name );
            $role->delete();
            Toastr::success('Role Succesfully Deleted ', 'Success');
        }


        return redirect()->back();
    }

}
