<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Producttype;
use App\Models\Transection;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\Facades\DataTables;

class TransectionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:distribution-list|distribution-create|distribution-edit|distribution-delete', ['only' => ['index', 'store', 'return', 'typedProducts', 'singleStock', 'multiAck', 'return']]);
        $this->middleware('permission:distribution-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:distribution-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:distribution-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Calculate statistics
        $stats = [
            'total_transactions' => Transection::count(),
            'active_assignments' => Transection::whereNull('return_date')->count(),
            'returned_items' => Transection::whereNotNull('return_date')->count(),
            'overdue_items' => Transection::whereNull('return_date')
                ->whereRaw('DATEDIFF(CURDATE(), issued_date) > 30')->count(),
            'unique_employees' => Transection::distinct('employee_id')->count('employee_id'),
            'total_items_out' => Transection::whereNull('return_date')->sum('quantity'),
        ];

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $types = Producttype::orderBy('name')->get();

        if ($request->ajax()) {
            $employee_id = $request->employee_id;
            $department_id = $request->department_id;
            $product_type = $request->product_type;
            $status = $request->status;
            $date_from = $request->date_from;
            $date_to = $request->date_to;

            $query = Transection::with(['employee.department', 'stock.product.type'])
                ->select([
                    'transections.*',
                    'employees.name as employee_name',
                    'employees.emply_id as employee_id_number',
                    'employees.designation',
                    'departments.name as department_name',
                    'products.title as product_name',
                    'producttypes.name as product_type_name',
                    'stocks.asset_tag',
                    'stocks.asset_condition',
                    'stocks.service_tag',
                    DB::raw('DATEDIFF(COALESCE(transections.return_date, CURDATE()), transections.issued_date) as days_with_asset'),
                    DB::raw('CASE
                        WHEN transections.return_date IS NOT NULL THEN "Returned"
                        WHEN DATEDIFF(CURDATE(), transections.issued_date) > 30 THEN "Overdue"
                        ELSE "Active"
                    END as transaction_status')
                ])
                ->leftJoin('employees', 'transections.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('stocks', 'transections.stock_id', '=', 'stocks.id')
                ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
                ->leftJoin('producttypes', 'products.producttype_id', '=', 'producttypes.id')
                ->when($employee_id, fn($q) => $q->where('transections.employee_id', $employee_id))
                ->when($department_id, fn($q) => $q->where('employees.department_id', $department_id))
                ->when($product_type, fn($q) => $q->where('products.producttype_id', $product_type))
                ->when($status, function ($q) use ($status) {
                    if ($status == 'active') {
                        $q->whereNull('transections.return_date');
                    } elseif ($status == 'returned') {
                        $q->whereNotNull('transections.return_date');
                    } elseif ($status == 'overdue') {
                        $q->whereNull('transections.return_date')
                            ->whereRaw('DATEDIFF(CURDATE(), transections.issued_date) > 30');
                    }
                })
                ->when($date_from, fn($q) => $q->whereDate('transections.issued_date', '>=', $date_from))
                ->when($date_to, fn($q) => $q->whereDate('transections.issued_date', '<=', $date_to))
                ->orderBy('transections.issued_date', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('employee_info', function ($row) {
                    return $row->employee_name . ' - ' . sprintf('%03d', $row->employee_id_number);
                })
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href="' . route('transections.show', $row->id) . '" class="btn btn-info btn-sm" title="View Details"><i class="material-icons">visibility</i></a>';

                    $returnBtn = '';
                    if (!$row->return_date && auth()->user()->can('distribution-edit')) {
                        $returnBtn = ' <button type="button" class="btn btn-success btn-sm mark-returned" data-id="' . $row->id . '" title="Mark as Returned"><i class="material-icons">assignment_turned_in</i></button>';
                    }

                    return $viewBtn . $returnBtn;
                })
                ->editColumn('issued_date', function ($row) {
                    return date('d M Y', strtotime($row->issued_date));
                })
                ->editColumn('return_date', function ($row) {
                    if ($row->return_date) {
                        return '<span class="badge" style="background: #4caf50; color: white; padding: 5px 10px; border-radius: 4px;">' . date('d M Y', strtotime($row->return_date)) . '</span>';
                    }
                    if (auth()->user()->can('distribution-edit')) {
                        return '<input type="date" class="form-control return-date-input" data-id="' . $row->id . '" style="width: 140px; display: inline-block;" />';
                    }
                    return '<span class="badge" style="background: #ff9800; color: white; padding: 5px 10px; border-radius: 4px;">Not Returned</span>';
                })
                ->addColumn('status_badge', function ($row) {
                    $colors = [
                        'Returned' => '#4caf50',
                        'Active' => '#2196f3',
                        'Overdue' => '#f44336'
                    ];
                    return '<span class="badge" style="background: ' . $colors[$row->transaction_status] . '; color: white; padding: 5px 10px; border-radius: 4px;">' . $row->transaction_status . '</span>';
                })
                ->addColumn('condition_badge', function ($row) {
                    $colors = [
                        'Good' => '#4caf50',
                        'Fair' => '#ff9800',
                        'Poor' => '#f44336',
                        'Damaged' => '#d32f2f',
                        'Obsolete' => '#9e9e9e'
                    ];
                    $color = $colors[$row->asset_condition] ?? '#2196f3';
                    return '<span class="badge" style="background: ' . $color . '; color: white; padding: 4px 8px; border-radius: 4px;">' . ($row->asset_condition ?? 'N/A') . '</span>';
                })
                ->rawColumns(['action', 'return_date', 'status_badge', 'condition_badge'])
                ->make(true);
        }

        return view('backend.admin.transection.index')->with(compact('employees', 'departments', 'types', 'stats'));
    }

    public function create()
    {
        // $stoks = Stock::where('product_status', 1)->where('is_assigned', 2)->get();
        $types = Producttype::all();
        $employees = Employee::all();
        // return $types;
        return view('backend.admin.transection.create')->with(compact('employees', 'types'));
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            'product_type' => 'required|integer',
            'product' => 'required|integer',
            'employee' => 'required|integer',
            'quantity' => 'required|integer',
            'date_of_issue' => 'required',

        ));

        $is_approved = 0;

        $type = Producttype::find($request->product_type);
        $stock = Stock::find($request->product);

        if ($type->slug == 'software') {

            $remain = $stock->quantity - $stock->assigned;

            if ($remain >= $request->quantity) {
                $is_approved = 1;
                $stock->assigned = $stock->assigned + $request->quantity;
            } else {
                $is_approved = 0;
            }
        } else {
            $is_approved = 1;
        }

        if ($is_approved == 1) {

            $transection = new Transection();
            $transection->stock_id = $stock->id;
            $transection->employee_id = $request->employee;
            $transection->quantity = $request->quantity;
            $transection->issued_date = $request->date_of_issue;

            // $transection->mouse          = $request->mouse;
            // $transection->pendrive       = $request->pendrive;
            // $transection->bag            = $request->laptop_bag;

            $transection->comment = $request->comment;
            $transection->save();

            $stock->is_assigned = 1;
            $stock->save();

            UserLogHelper::log('create', 'Assigned a Product To Employee : ' . $transection->id);

            // if($type->slug == 'software'){
            //     Stock::where('id',$request->product)->update(['assigned'=> $current_stock]);
            // }else {
            //     Stock::where('id',$request->product)->update(['is_assigned'=> 1]);
            // }


            if ($request->print_ack == 1) {
                Toastr::success(' Succesfully Saved ', 'Success');
                return redirect()->route('transections.ack', $transection->id);
            }

            Toastr::success(' Succesfully Saved ', 'Success');
            return redirect()->route('transections.index');

        } else {
            Toastr::error('No license is available for assign', 'Error');
            return redirect()->back()->withInput();
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'date_of_return' => 'required',
        ));

        // return $request->all();

        // Transection::where('id',$id)->update(['return_date'=> ]);

        $transection = Transection::find($id);

        $transection->return_date = $request->date_of_return;
        $transection->save();

        if ($transection->stock->producttype->slug == 'software') {
            $stock_get = Stock::find($transection->stock_id);
            $stock_get->assigned = $stock_get->assigned - $transection->quantity;
            $stock_get->save();
            if ($stock_get->assigned == 0) {
                Stock::where('id', $transection->stock_id)->update(['is_assigned' => 2]);
            }

        } else {
            Stock::where('id', $transection->stock_id)->update(['is_assigned' => 2]);
        }

        UserLogHelper::log('update', 'Updated Stock Return from Employee : ' . $transection->id);

        Toastr::success(' Succesfully Updated ', 'Success');
        return redirect()->back();


    }

    public function show($id)
    {
        $transection = Transection::find($id);
        return view('backend.admin.transection.show')->with(compact('transection'));
    }


    public function typedProducts($id)
    {

        $type = Producttype::find($id);

        $products = DB::table('stocks')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->join('producttypes', 'producttypes.id', '=', 'stocks.producttype_id')
            //->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('stocks.id', 'stocks.asset_tag', 'stocks.service_tag', 'stocks.quantity', 'products.title', 'products.brand', 'products.model', 'producttypes.slug')
            ->where('stocks.producttype_id', $id);
        //->where('product_status', 1);

        if ($type->slug == 'software') {
            $products->where('quantity', '>', 0);
        } else {
            $products->where('is_assigned', 2);
        }

        // ->get();

        return response()->json([
            'products' => $products->get(),
            'type' => $type->slug,
        ]);

    }

    public function singleStock($id)
    {
        $stock = Stock::find($id);
        return $stock;
    }

    public function ack($id)
    {
        $transection = Transection::findOrFail($id);

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.ack', compact('transection'))->setPaper('a4');

        return $pdf->stream('grn-' . $transection->date . '.pdf');


    }


    public function multiAck(Request $request)
    {

        // return $request->all();
        $transections = Transection::whereIn('id', $request->print_ack)->get();
        $employee = Employee::find($request->emply_id);
        $isdate = $request->issued_date;

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.ac-multiple', compact('transections', 'employee', 'isdate'))->setPaper('a4');
        // return $pdf;

        return $pdf->stream('ack-' . $employee->id . '.pdf');


    }

    public function return($id)
    {
        $transection = Transection::findOrFail($id);

        // $purchase = Purchase::findOrFail($id);
        $pdf = Pdf::loadView('backend.admin.pdf.return', compact('transection'))->setPaper('a4');

        return $pdf->stream('return-' . $transection->employee->emply_id . '-' . $transection->return_date . '.pdf');
    }

    public function markReturned(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date'
        ]);

        $transaction = Transection::findOrFail($id);
        $transaction->return_date = $request->return_date;
        $transaction->save();

        // Update stock assignment status
        $stock = Stock::find($transaction->stock_id);
        if ($stock) {
            $stock->is_assigned = 2; // Available
            $stock->save();
        }

        UserLogHelper::log('update', 'Marked transaction as returned: ' . $transaction->id);

        return response()->json(['success' => true, 'message' => 'Transaction marked as returned successfully']);
    }
}
