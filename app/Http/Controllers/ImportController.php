<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
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
        $this->middleware('permission:import-create', ['only' => ['index', 'store']]);
    }
    public function index()
    {
        return view('backend.import');
    }
    public function store(Request $request)
    {

        $request->validate([
            'import_table' => 'required|string',
            'csv_file' => 'required|file|mimes:csv',
        ]);

        // return $request->all();

        $status = false;


        try {
            // Check if the file is valid
            if (!$request->file('csv_file')->isValid()) {
                Toastr::error('Invalid file uploaded', 'Error');
                return redirect()->back();
            }

            // Import and update records
            if ($request->import_table == 'product') {
                Excel::import(new ProductImport, $request->file('csv_file'));
                $status = true;
            }

            if ($request->import_table == 'purchase_product') {
                Excel::import(new PurchaseProductImport, $request->file('csv_file'));
                $status = true;
            }

            if ($request->import_table == 'inventory') {
                Excel::import(new StockAllImport, $request->file('csv_file'));

            }

            if ($request->import_table == 'transection') {
                TransectionImport::$errors = []; // Reset errors
                Excel::import(new TransectionImport, $request->file('csv_file'));
                if (empty(TransectionImport::$errors)) {
                    $status = true;
                }
            }

            if ($request->import_table == 'employee') {
                Excel::import(new EmployeesImport, $request->file('csv_file'));
                $status = true;

            }

            if (!$status) {
                Toastr::error('Invalid Import', 'Error');
                return redirect()->back();
            } else {
                Toastr::success('File imported successfully', 'Success');
            }
        } catch (\Exception $e) {
            Toastr::error('File upload error: ' . $e->getMessage(), 'Error');
        }

        return redirect()->back();
    }


}
