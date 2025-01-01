<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producttype;
use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Stock;
use App\Models\Transection;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::orderBy('emply_id', 'asc')->get();
        $departments = Department::all();


        if (isset($request->department_id)) {
            $employees = Employee::where('department_id', $request->department_id)
                ->orderBy('emply_id', $request->order_by)
                ->get();
        }

        return view('backend.report.index')->with(compact('employees', 'departments'));
    }

    public function getReport(Request $request)
    {

        $transections = Transection::all();
        $employee = Employee::where('emply_id', $request->employee_id)->first();
        $employees = Employee::all();
        return view('backend.report.index')->with(compact('employee', 'employees'));
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        return view('backend.report.employee')->with(compact('employee'));
    }

    public function stocks(Request $request)
    {

        $stocks = Producttype::all();

        return view('backend.report.stocks')->with(compact('stocks'));
    }

    function stockDetails(Request $request, $id)
    {
        $stocks = Stock::where('producttype_id', $id)->get();

        return view('backend.report.stock-details')->with(compact('stocks'));
        return $stocks;
    }

    public function transections(Request $request)
    {

        // Query with filters
        $query = Transection::query();

        //return $request->all();

        $transections = Transection::orderBy('employee_id', 'asc')->get();
        $employees = Employee::all();

        if (isset($request->employee_id)) {

            $transections = Transection::where('employee_id', $request->employee_id)
                ->orderBy('employee_id', $request->order_by)->get();
            //return $transections;
        }

        return view('backend.report.transections')->with(compact('transections', 'employees'));
    }
}
