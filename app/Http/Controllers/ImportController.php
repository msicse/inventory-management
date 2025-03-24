<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Imports\PurchaseProductImport;
use App\Imports\StockAllImport;
use App\Imports\TransectionImport;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:import-create', ['only' => ['index','store']]);
    }
    public function index() {
        return view('backend.import');
    }
    public function store(Request $request) {

        $request->validate([
            'import_table' => 'required|string',
            'csv_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // return $request->all();

        // Import and update records
        if($request->import_table == 'product'){
            Excel::import(new ProductImport, $request->file('csv_file'));
        }

        if($request->import_table == 'purchase_product'){
            Excel::import(new PurchaseProductImport, $request->file('csv_file'));
        }

        if($request->import_table == 'inventory'){
            Excel::import(new StockAllImport, $request->file('csv_file'));
        }

        if($request->import_table == 'transection'){
            Excel::import(new TransectionImport, $request->file('csv_file'));
        }

        Toastr::success('Succesfully Imported ', 'Success');
        return redirect()->back();
    }


}
