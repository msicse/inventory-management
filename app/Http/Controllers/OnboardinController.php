<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Stock;
use App\Models\Transection;
use Barryvdh\DomPDF\Facade\Pdf;


class OnboardinController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:onboarding-list', ['only' => ['onboarding', 'postOnboarding']]);
        $this->middleware('permission:onboarding-create', ['only' => ['print']]);
    }

    public function onboarding()
    {
        $employees = Employee::all();
        return view('backend.admin.onbordings')->with(compact('employees'));
    }

    public function postOnboarding(Request $request)
    {
        // return $request->all();
        $employees = Employee::all();
        $employee = Employee::where('id', $request->employee)->first();
        return view('backend.admin.onbordings')->with(compact('employee', 'employees'));
    }

    public function print(Request $request, $id)
    {


        $employee = Employee::find($id);

        $laptop = $request->laptop;
        $mobile = $request->mobile;
        $pendrive = $request->pendrive;
        $mouse = $request->mouse;
        $camera = $request->camera;
        $laptop_bag = $request->laptop_bag;
        $sdcard = $request->sdcard;
        $manual = $request->manual;
        // $employee = Transection::where('emply_id', $id)->get();

        $transection = Transection::where('employee_id', $id)->first();

        // $purchase = Purchase::findOrFail($id);

        if( $request->return == 1 ){
            $pdf = Pdf::loadView('backend.admin.pdf.return-multiple', compact('employee', 'laptop', 'mobile', 'pendrive', 'mouse', 'camera', 'laptop_bag', 'sdcard', 'manual'))->setPaper('a4');
            return $pdf->stream($employee->name.'-'.$employee->emply_id.'-return.pdf');
        } else {
            $pdf = Pdf::loadView('backend.admin.pdf.ack-onboarding', compact('employee', 'laptop', 'mobile', 'pendrive', 'mouse', 'camera', 'laptop_bag', 'sdcard', 'manual'))->setPaper('a4');


            return $pdf->stream($employee->name.'-'.$employee->emply_id.'-ack.pdf');
        }




    }


}
