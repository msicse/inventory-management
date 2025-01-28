<?php

namespace App\Http\Controllers\Admin;

use Str;
use Hash;
use Image;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
         if (Auth::user()->hasRole('super-admin')) {
            $users = User::latest()->get();
        } else {
            $users = User::whereNot('is_admin', 1)->get();
        }

         return view('backend.admin.user.index')->with(compact('users'));
    }
    public function create()
    {
        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::pluck('name','name');
        } else {
            $roles = Role::whereNot('name', 'super-admin')->pluck('name','name');
        }

        return view('backend.admin.user.create')->with(compact('roles'));
    }
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request,array(
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|max:255|unique:users,email',
            'employee_id' => 'required|max:255|unique:users,employee_id',
            'roles' => 'required|max:255',
            'password' => 'required|max:255|same:confirm-password',
        ));

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);


       $roles = $request->input('roles');

       $user = User::create($input);

        if (Auth::user()->hasRole('super-admin')) {
            $user->assignRole($roles);
        } else {
            if (in_array("super-admin", $roles)){
                $roles = array_values(array_diff($roles, ["super-admin"]));
                $user->assignRole($roles);
            }
        }

        if($user->hasRole("super-admin")){
            $user->update(["is_admin" => 1]);
        }

        UserLogHelper::log('create', 'Created a New User : '. $user->email );

        Toastr::success('User Succesfully Created ', 'Success');
        return redirect()->route('users.index');
    }

    public function show($id):View
    {
        $user = User::find($id);
        return view("backend.admin.user.show", compact("user"));
    }

    public function edit($id)
    {


        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::pluck('name','name');
        } else {
            $roles = Role::whereNot('name', 'super-admin')->pluck('name','name');
        }

        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();

        return view('backend.admin.user.edit')->with(compact('roles', 'user', 'userRole'));
    }


    public function update(Request $request, $id):RedirectResponse
    {
        //return $request->all();
        $this->validate($request,array(
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,'.$id,
            'email' => 'required|max:255|unique:users,email,'.$id,
            'employee_id' => 'required|max:255|unique:users,employee_id,'.$id,
            'roles' => 'required|max:255',
            'password' => 'max:255|same:confirm-password',
        ));


        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        $roles = $request->input('roles');

        if (Auth::user()->hasRole('super-admin')) {
            $user->syncRoles($roles);
        } else {
            if (in_array("super-admin", $roles)){
                $roles = array_values(array_diff($roles, ["super-admin"]));
                $user->syncRoles($roles);
            }
        }

        if($user->hasRole("super-admin")){
            $user->update(["is_admin" => 1]);
        }else{
            $user->update(["is_admin" => 2]);
        }

        UserLogHelper::log('update', 'Updated User : '. $user->id );

        Toastr::success('User Succesfully Updated ', 'Success');
        return redirect()->route('users.index');
    }


    public function destroy($id)//:RedirectResponse
    {

        $user = User::find($id);

        $roles = $user->getRoleNames();

        if (Auth::user()->hasRole('super-admin')) {
            foreach( $roles as $role ){
                $user->removeRole($role);
            }
            $user->delete();

        } else {
            if ($user->hasRole('super-admin')){
                Toastr::Error('You have no Permission ', 'Error');
                return redirect()->back();
            }else {
                foreach( $roles as $role ){
                    $user->removeRole($role);
                }
                $user->delete();
            }
        }

        UserLogHelper::log('update', 'Updated User : '. $user->id );
        Toastr::success('User Succesfully Deleted ', 'Success');
        return redirect()->back();
    }
}
