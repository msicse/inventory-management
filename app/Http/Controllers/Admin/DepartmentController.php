<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class DepartmentController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:department-list|department-create|department-edit|department-delete', ['only' => ['index','store']]);
         $this->middleware('permission:department-create', ['only' => ['store']]);
         $this->middleware('permission:department-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:department-delete', ['only' => ['destroy']]);
    }


    public function index()
    {
         $departments = Department::all();
         return view('backend.admin.departments')->with(compact('departments'));
    }
    public function store(Request $request)
    {
        $this->validate($request,array(
            'name'          => 'required|max:255',
            'short_name'    => 'required|max:255',
        ));

        $slug  = Str::slug($request->name);
        $department = new Department();
        $department->name         = $request->name;
        $department->short_name   = $request->short_name;
        $department->slug        = $slug;
        $department->save();

        UserLogHelper::log('create', 'Created department: '. $department->name);

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $department = Department::find($id);
        return $department;
    }

    public function update(Request $request, $id)
    {
         $this->validate($request,array(
            'name'          => 'required|max:255',
            'short_name'    => 'required|max:255',
        ));

        $department = Department::find($id);
        $department->name         = $request->name;
        $department->short_name   = $request->short_name;
        //$department->slug        = $slug;
        $department->save();

        UserLogHelper::log('update', 'Updated department: '. $department->name);

        Toastr::success(' Succesfully Updated ', 'Success');



        // $employees = Employee::where('department_id', '=', $id )->exists();

        // if( $employees ){

        //     Toastr::error('Delete Resticted  ', 'Error');
        // } else {
        //     //$slug  = str_slug($request->name);
        //     $department = Department::find($id);
        //     $department->name         = $request->name;
        //     $department->short_name   = $request->short_name;
        //     //$department->slug        = $slug;
        //     $department->save();
        //     Toastr::success(' Succesfully Updated ', 'Success');
        // }


        return redirect()->back();
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        $employees = Employee::where('department_id', '=', $id )->exists();


        if( $employees ){

            Toastr::error('Delete Resticted  ', 'Error');
        } else {
            $department->delete();
            UserLogHelper::log('delete', 'Deleted department: '. $department->name);
            Toastr::success('Succesfully Deleted  ', 'Success');
        }

        return redirect()->back();


    }
}
