<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Stock;
use App\Models\Transection;


class ReportController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $transections = Transection::all();
        $departments = Department::all();
        return view('backend.report.index')->with(compact('employees', 'transections', 'departments'));
    }
}
