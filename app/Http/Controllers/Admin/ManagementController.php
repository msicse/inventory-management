<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Employee;

use App\Models\Transection;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ManagementController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:management-all', ['only' => ['employees', 'editEmployee', 'updateEmployee', 'products', 'updateProducts']]);
    }
    public function employees()
    {
        $employees = Employee::where('status', 1)->get();



        return view('backend.admin.management.employees')->with(compact("employees"));
    }


    public function editEmployee($id)
    {
        $employee = Employee::find($id);

        return view('backend.admin.management.employee-edit')->with(compact("employee"));
    }


    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::find($id);

        $this->validate($request, array(
            'date_of_resign'    => 'required',
        ));




        //if(isset($request->status)){

            $transection_count = $tran = Transection::where('employee_id', $employee->id)->whereNull('return_date')->count();

            if( $transection_count > 0 ){
                foreach( $employee->transections as $transection){
                    //return $transection;

                    $tran = Transection::whereNull('return_date')->first();
                    $tran->return_date   = $request->date_of_resign;
                    $tran->save();

                    Stock::where('id',$transection->stock_id)->update(['is_assigned'=> 2]);

                    UserLogHelper::log('update', 'Updated Employee Status: '.$employee->name. ' Employee ID: ' . $employee->emply_id );
                }
            }

        // } else{
        //     return "Status is not ok";
        // }



        $employee->resign_date  = $request->date_of_resign;
        $employee->status  = 2;
        $employee->save();

        Toastr::success('Successfully Updated', 'Success');

        return redirect()->route('management.employees');

    }

    public function products()
    {
        $products = Stock::all();

        return view('backend.admin.management.products')->with(compact("products"));
    }

    public function updateProducts(Request $request, $id)
    {

        //return $request->all();
        if( $request->submit == 'active' ){
            Stock::where('id', $id)->update(['product_status'=> 1]);
        } elseif ($request->submit == 'poor') {
            Stock::where('id', $id)->update(['product_status'=> 2]);
        } else {
            Stock::where('id', $id)->update(['product_status'=> 3]);
        }
        UserLogHelper::log('update', 'Updated Inventory Product Status: '.$id );
        Toastr::success('Successfully Updated', 'Success');
        return redirect()->back();
    }
}
