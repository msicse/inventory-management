<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Producttype;
use App\Models\Transection;
use App\Models\ConsumableMovement;
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
        $this->middleware('permission:distribution-list|distribution-create|distribution-edit|distribution-delete', ['only' => ['index', 'store', 'return', 'typedProducts', 'singleStock', 'multiAck', 'return', 'consumableIndex', 'consumableStore', 'typedConsumableProducts', 'markConsumableReturned']]);
        $this->middleware('permission:distribution-create', ['only' => ['create', 'store', 'consumableCreate', 'consumableStore']]);
        $this->middleware('permission:distribution-edit', ['only' => ['edit', 'update', 'markConsumableReturned']]);
        $this->middleware('permission:distribution-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Calculate statistics
        $stats = [
            'total_transactions' => Transection::whereHas('stock.product.type', function ($q) {
                $q->where('asset_class', '!=', 'CONSUMABLE');
            })->count(),
            'active_assignments' => Transection::whereNull('return_date')->whereHas('stock.product.type', function ($q) {
                $q->where('asset_class', '!=', 'CONSUMABLE');
            })->count(),
            'returned_items' => Transection::whereNotNull('return_date')->whereHas('stock.product.type', function ($q) {
                $q->where('asset_class', '!=', 'CONSUMABLE');
            })->count(),
            'overdue_items' => Transection::whereNull('return_date')
                ->whereRaw('DATEDIFF(CURDATE(), issued_date) > 30')
                ->whereHas('stock.product.type', function ($q) {
                    $q->where('asset_class', '!=', 'CONSUMABLE');
                })->count(),
            'unique_employees' => Transection::whereHas('stock.product.type', function ($q) {
                $q->where('asset_class', '!=', 'CONSUMABLE');
            })->distinct('employee_id')->count('employee_id'),
            'total_items_out' => Transection::whereNull('return_date')->whereHas('stock.product.type', function ($q) {
                $q->where('asset_class', '!=', 'CONSUMABLE');
            })->sum('quantity'),
        ];

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $types = Producttype::orderBy('name')->get();

        if ($request->ajax()) {
            $employee_id = $request->employee_id;
            $department_id = $request->department_id;
            $product_type = $request->product_type;
            $status = $request->status;
            $asset_mode = $request->asset_mode;
            $date_from = $request->date_from;
            $date_to = $request->date_to;

            $returnedSubquery = DB::table('consumable_movements')
                ->select('transection_id', DB::raw('SUM(qty) as returned_qty'))
                ->where('movement_type', 'RETURN')
                ->groupBy('transection_id');

            $query = Transection::with(['employee.department', 'stock.product.type'])
                ->select([
                    'transections.*',
                    'employees.name as employee_name',
                    'employees.emply_id as employee_id_number',
                    'employees.designation',
                    'departments.name as department_name',
                    'products.title as product_name',
                    'producttypes.name as product_type_name',
                    'producttypes.asset_class',
                    'stocks.asset_tag',
                    'stocks.asset_condition',
                    'stocks.service_tag',
                    DB::raw('COALESCE(cmr.returned_qty, 0) as returned_qty'),
                    DB::raw('CASE
                        WHEN transections.return_date IS NULL THEN transections.quantity
                        ELSE 0
                    END as outstanding_qty'),
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
                ->leftJoinSub($returnedSubquery, 'cmr', function ($join) {
                    $join->on('cmr.transection_id', '=', 'transections.id');
                })
                ->where(function ($q) {
                    $q->where('producttypes.asset_class', '!=', 'CONSUMABLE')
                      ->orWhereNull('producttypes.asset_class');
                })
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
                        $returnBtn = ' <button type="button" class="btn btn-success btn-sm mark-returned" data-id="' . $row->id . '" title="Quick Return (Today)"><i class="material-icons">assignment_turned_in</i></button>';
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
                        return '<div class="return-controls" data-id="' . $row->id . '">
                            <input type="date" class="form-control return-date-input" style="width: 140px; display: inline-block; margin-right: 6px;" />
                            <button type="button" class="btn btn-success btn-sm submit-return-btn" title="Submit Return"><i class="material-icons" style="font-size:16px;">done</i></button>
                        </div>';
                    }
                    return '<span class="badge" style="background: #ff9800; color: white; padding: 5px 10px; border-radius: 4px;">Not Returned</span>';
                })
                ->addColumn('asset_mode_badge', function ($row) {
                    if (strtoupper((string) $row->asset_class) === 'CONSUMABLE') {
                        return '<span class="badge" style="background:#ff9800; color:white; padding:5px 10px; border-radius:4px;">Consumable</span>';
                    }

                    return '<span class="badge" style="background:#607d8b; color:white; padding:5px 10px; border-radius:4px;">Fixed</span>';
                })
                ->editColumn('outstanding_qty', function ($row) {
                    $qty = (int) $row->outstanding_qty;
                    $color = $qty > 0 ? '#f44336' : '#4caf50';
                    return '<span class="badge" style="background:' . $color . '; color:white; padding:5px 10px; border-radius:4px;">' . $qty . '</span>';
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
                ->rawColumns(['action', 'return_date', 'status_badge', 'condition_badge', 'asset_mode_badge', 'outstanding_qty'])
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
        $stock = Stock::with('product')->find($request->product);

        if (!$stock || !$type) {
            Toastr::error('Invalid stock or product type selected', 'Error');
            return redirect()->back()->withInput();
        }

        $issueQty = (int) $request->quantity;

        // Consumables are handled in separate module and should not be created in fixed-asset transactions.
        if (strtoupper((string) ($type->asset_class ?? 'FIXED')) === 'CONSUMABLE') {
            Toastr::error('Consumable distribution is managed from Consumable Distribution module only', 'Error');
            return redirect()->route('consumable.transections.index');
        }

        if ($type->slug == 'software') {

            $remain = (int) $stock->quantity - (int) $stock->assigned;

            if ($remain >= $issueQty) {
                $is_approved = 1;
                $stock->assigned = (int) $stock->assigned + $issueQty;
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
            $transection->quantity = $issueQty;
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
            'return_quantity' => 'nullable|integer|min:1',
        ));

        // return $request->all();

        // Transection::where('id',$id)->update(['return_date'=> ]);

        $transection = Transection::find($id);

        if (!$transection) {
            Toastr::error('Transaction not found', 'Error');
            return redirect()->back();
        }

        $stock = Stock::with('product', 'producttype')->find($transection->stock_id);
        if (!$stock) {
            Toastr::error('Stock not found', 'Error');
            return redirect()->back();
        }

        $isConsumable = strtoupper((string) ($stock->producttype->asset_class ?? '')) === 'CONSUMABLE' || (int) ($stock->product->is_consumable ?? 0) === 1;
        if ($isConsumable) {
            try {
                DB::transaction(function () use ($request, $transection, $stock) {
                    $issuedQty = (int) $transection->quantity;
                    $alreadyReturned = (int) ConsumableMovement::where('transection_id', $transection->id)
                        ->where('movement_type', 'RETURN')
                        ->sum('qty');
                    $outstanding = max(0, $issuedQty - $alreadyReturned);

                    $returnQty = $request->return_quantity ? (int) $request->return_quantity : $outstanding;
                    if ($returnQty <= 0 || $returnQty > $outstanding) {
                        throw new \InvalidArgumentException('Invalid return quantity for consumable transaction');
                    }

                    $lockedStock = Stock::lockForUpdate()->findOrFail($stock->id);
                    $lockedStock->quantity = (int) $lockedStock->quantity + $returnQty;
                    $lockedStock->assigned = max(0, (int) $lockedStock->assigned - $returnQty);
                    $lockedStock->is_assigned = ((int) $lockedStock->assigned > 0) ? 1 : 2;
                    $lockedStock->save();

                    ConsumableMovement::create([
                        'stock_id' => $lockedStock->id,
                        'employee_id' => $transection->employee_id,
                        'transection_id' => $transection->id,
                        'movement_type' => 'RETURN',
                        'qty' => $returnQty,
                        'movement_date' => $request->date_of_return,
                        'remarks' => $request->comment,
                        'created_by' => auth()->id(),
                    ]);

                    $newReturnedTotal = $alreadyReturned + $returnQty;
                    if ($newReturnedTotal >= $issuedQty) {
                        $transection->return_date = $request->date_of_return;
                        $transection->save();
                    }
                });
            } catch (\InvalidArgumentException $e) {
                Toastr::error($e->getMessage(), 'Error');
                return redirect()->back();
            } catch (\Throwable $e) {
                report($e);
                Toastr::error('Failed to process consumable return', 'Error');
                return redirect()->back();
            }

            UserLogHelper::log('update', 'Updated consumable return from employee: ' . $transection->id);
            Toastr::success(' Succesfully Updated ', 'Success');
            return redirect()->back();
        }

        $transection->return_date = $request->date_of_return;
        $transection->save();

        if ($stock->producttype->slug == 'software') {
            $stock_get = Stock::find($transection->stock_id);
            $stock_get->assigned = (int) $stock_get->assigned - (int) $transection->quantity;
            $stock_get->save();
            if ((int) $stock_get->assigned == 0) {
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
            ->select('stocks.id', 'stocks.asset_tag', 'stocks.service_tag', 'stocks.quantity', 'products.title', 'products.brand', 'products.model', 'producttypes.slug', 'products.is_consumable')
            ->where('stocks.producttype_id', $id);
        //->where('product_status', 1);

        if ($type->slug == 'software') {
            $products->where('quantity', '>', 0);
        } else {
                        $products->where(function ($q) {
                                        $q->where('producttypes.asset_class', '!=', 'CONSUMABLE')
                                            ->orWhereNull('producttypes.asset_class');
                                })
                ->where('stocks.is_assigned', 2);
        }

        // ->get();

        return response()->json([
            'products' => $products->get(),
            'type' => $type->slug,
        ]);

    }

    public function consumableIndex(Request $request)
    {
        $stats = [
            'total_transactions' => ConsumableMovement::where('movement_type', 'ISSUE')->count(),
            'active_assignments' => ConsumableMovement::from('consumable_movements as cm_issue')
                ->leftJoinSub(
                    DB::table('consumable_movements')
                        ->select('issue_movement_id', DB::raw('SUM(qty) as returned_qty'))
                        ->where('movement_type', 'RETURN')
                        ->whereNotNull('issue_movement_id')
                        ->groupBy('issue_movement_id'),
                    'ri',
                    function ($join) {
                        $join->on('ri.issue_movement_id', '=', 'cm_issue.id');
                    }
                )
                ->where('cm_issue.movement_type', 'ISSUE')
                ->whereRaw('GREATEST(cm_issue.qty - COALESCE(ri.returned_qty, 0), 0) > 0')
                ->count(),
            'returned_items' => ConsumableMovement::from('consumable_movements as cm_issue')
                ->leftJoinSub(
                    DB::table('consumable_movements')
                        ->select('issue_movement_id', DB::raw('SUM(qty) as returned_qty'))
                        ->where('movement_type', 'RETURN')
                        ->whereNotNull('issue_movement_id')
                        ->groupBy('issue_movement_id'),
                    'ri',
                    function ($join) {
                        $join->on('ri.issue_movement_id', '=', 'cm_issue.id');
                    }
                )
                ->where('cm_issue.movement_type', 'ISSUE')
                ->whereRaw('GREATEST(cm_issue.qty - COALESCE(ri.returned_qty, 0), 0) = 0')
                ->count(),
            'unique_employees' => ConsumableMovement::where('movement_type', 'ISSUE')->distinct('employee_id')->count('employee_id'),
            'total_items_out' => ConsumableMovement::where('movement_type', 'ISSUE')->sum('qty'),
        ];

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $types = Producttype::whereHas('products', function ($q) {
            $q->whereNotNull('id');
        })->where('asset_class', 'CONSUMABLE')->orderBy('name')->get();

        if ($request->ajax()) {
            $returnedSubquery = DB::table('consumable_movements')
                ->select('issue_movement_id', DB::raw('SUM(qty) as returned_qty'), DB::raw('MAX(movement_date) as latest_return_date'))
                ->where('movement_type', 'RETURN')
                ->whereNotNull('issue_movement_id')
                ->groupBy('issue_movement_id');

            $query = ConsumableMovement::from('consumable_movements as cm_issue')
                ->select([
                    'cm_issue.id',
                    'cm_issue.stock_id',
                    'cm_issue.employee_id',
                    'cm_issue.qty',
                    'cm_issue.movement_date',
                    'cm_issue.remarks',
                    'employees.name as employee_name',
                    'employees.emply_id as employee_id_number',
                    'departments.name as department_name',
                    'products.title as product_name',
                    'producttypes.name as product_type_name',
                    'stocks.asset_tag',
                    'stocks.service_tag',
                    DB::raw('COALESCE(cmr.returned_qty, 0) as returned_qty'),
                    DB::raw('GREATEST(cm_issue.qty - COALESCE(cmr.returned_qty, 0), 0) as outstanding_qty'),
                    DB::raw('CASE WHEN GREATEST(cm_issue.qty - COALESCE(cmr.returned_qty, 0), 0) = 0 THEN cmr.latest_return_date ELSE NULL END as return_date'),
                    DB::raw('DATEDIFF(COALESCE(CASE WHEN GREATEST(cm_issue.qty - COALESCE(cmr.returned_qty, 0), 0) = 0 THEN cmr.latest_return_date END, CURDATE()), cm_issue.movement_date) as days_with_asset'),
                ])
                ->leftJoin('employees', 'cm_issue.employee_id', '=', 'employees.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('stocks', 'cm_issue.stock_id', '=', 'stocks.id')
                ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
                ->leftJoin('producttypes', 'products.producttype_id', '=', 'producttypes.id')
                ->leftJoinSub($returnedSubquery, 'cmr', function ($join) {
                    $join->on('cmr.issue_movement_id', '=', 'cm_issue.id');
                })
                ->where('cm_issue.movement_type', 'ISSUE')
                ->where('producttypes.asset_class', 'CONSUMABLE')
                ->when($request->employee_id, fn($q) => $q->where('cm_issue.employee_id', $request->employee_id))
                ->when($request->department_id, fn($q) => $q->where('employees.department_id', $request->department_id))
                ->when($request->product_type, fn($q) => $q->where('products.producttype_id', $request->product_type))
                ->when($request->status, function ($q) use ($request) {
                    if ($request->status === 'active') {
                        $q->whereRaw('GREATEST(cm_issue.qty - COALESCE(cmr.returned_qty, 0), 0) > 0');
                    }
                    if ($request->status === 'returned') {
                        $q->whereRaw('GREATEST(cm_issue.qty - COALESCE(cmr.returned_qty, 0), 0) = 0');
                    }
                })
                ->when($request->date_from, fn($q) => $q->whereDate('cm_issue.movement_date', '>=', $request->date_from))
                ->when($request->date_to, fn($q) => $q->whereDate('cm_issue.movement_date', '<=', $request->date_to))
                ->orderBy('cm_issue.movement_date', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('employee_info', function ($row) {
                    return $row->employee_name . ' - ' . sprintf('%03d', $row->employee_id_number);
                })
                ->addColumn('status_badge', function ($row) {
                    if ((int) $row->outstanding_qty > 0) {
                        return '<span class="badge" style="background:#2196f3; color:white; padding:5px 10px; border-radius:4px;">Active</span>';
                    }
                    return '<span class="badge" style="background:#4caf50; color:white; padding:5px 10px; border-radius:4px;">Returned</span>';
                })
                ->editColumn('issued_date', function ($row) {
                    return date('d M Y', strtotime($row->movement_date));
                })
                ->editColumn('return_date', function ($row) {
                    if ($row->return_date) {
                        return '<span class="badge" style="background: #4caf50; color: white; padding: 5px 10px; border-radius: 4px;">' . date('d M Y', strtotime($row->return_date)) . '</span>';
                    }

                    return '<span class="badge" style="background:#ff9800; color:white; padding:5px 10px; border-radius:4px;">Not Returned</span>';
                })
                ->editColumn('outstanding_qty', function ($row) {
                    $qty = (int) $row->outstanding_qty;
                    $color = $qty > 0 ? '#f44336' : '#4caf50';
                    return '<span class="badge" style="background:' . $color . '; color:white; padding:5px 10px; border-radius:4px;">' . $qty . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $returnBtn = '';
                    if ((int) $row->outstanding_qty > 0 && auth()->user()->can('distribution-edit')) {
                        $returnBtn = ' <button type="button" class="btn btn-success btn-sm open-return-panel"
                            data-id="' . $row->id . '"
                            data-employee="' . e($row->employee_name ?? 'N/A') . '"
                            data-product="' . e($row->product_name ?? 'N/A') . '"
                            data-outstanding="' . (int) $row->outstanding_qty . '"
                            title="Return Quantity"><i class="material-icons">assignment_return</i></button>';
                    }

                            return $returnBtn ?: '<span class="text-muted">-</span>';
                })
                ->rawColumns(['status_badge', 'return_date', 'outstanding_qty', 'action'])
                ->make(true);
        }

        return view('backend.admin.consumable-transaction.index')->with(compact('employees', 'departments', 'types', 'stats'));
    }

    public function consumableCreate()
    {
        $types = Producttype::whereHas('products', function ($q) {
            $q->whereNotNull('id');
        })->where('asset_class', 'CONSUMABLE')->orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();

        return view('backend.admin.consumable-transaction.create')->with(compact('employees', 'types'));
    }

    public function consumableStore(Request $request)
    {
        $this->validate($request, [
            'product_type' => 'required|integer',
            'product' => 'required|integer',
            'employee' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'date_of_issue' => 'required',
        ]);

        $stock = Stock::with('product')->find($request->product);

        $type = $stock?->product?->type;
        if (!$stock || strtoupper((string) ($type->asset_class ?? 'FIXED')) !== 'CONSUMABLE') {
            Toastr::error('Please select a valid consumable stock', 'Error');
            return redirect()->back()->withInput();
        }

        $issueQty = (int) $request->quantity;
        if ((int) $stock->quantity < $issueQty) {
            Toastr::error('Insufficient consumable stock available', 'Error');
            return redirect()->back()->withInput();
        }

        try {
            DB::transaction(function () use ($request, $stock, $issueQty) {
                $lockedStock = Stock::lockForUpdate()->findOrFail($stock->id);

                if ((int) $lockedStock->quantity < $issueQty) {
                    throw new \InvalidArgumentException('Insufficient consumable stock available');
                }

                $lockedStock->quantity = max(0, (int) $lockedStock->quantity - $issueQty);
                $lockedStock->assigned = (int) $lockedStock->assigned + $issueQty;
                $lockedStock->is_assigned = ((int) $lockedStock->assigned > 0) ? 1 : 2;
                $lockedStock->save();

                $issueMovement = ConsumableMovement::create([
                    'stock_id' => $lockedStock->id,
                    'employee_id' => $request->employee,
                    'transection_id' => null,
                    'movement_type' => 'ISSUE',
                    'qty' => $issueQty,
                    'movement_date' => $request->date_of_issue,
                    'remarks' => $request->comment,
                    'created_by' => auth()->id(),
                ]);

                UserLogHelper::log('create', 'Issued consumable product to employee. Movement ID: ' . $issueMovement->id);
            });
        } catch (\InvalidArgumentException $e) {
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        } catch (\Throwable $e) {
            report($e);
            Toastr::error('Failed to issue consumable stock', 'Error');
            return redirect()->back()->withInput();
        }

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->route('consumable.transections.index');
    }

    public function markConsumableReturned(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'return_quantity' => 'required|integer|min:1',
            'comment' => 'nullable|string',
        ]);

        $issueMovement = ConsumableMovement::where('movement_type', 'ISSUE')->findOrFail($id);

        try {
            DB::transaction(function () use ($request, $issueMovement) {
                $issuedQty = (int) $issueMovement->qty;
                $alreadyReturned = (int) ConsumableMovement::where('movement_type', 'RETURN')
                    ->where('issue_movement_id', $issueMovement->id)
                    ->sum('qty');
                $outstanding = max(0, $issuedQty - $alreadyReturned);

                $returnQty = (int) $request->return_quantity;
                if ($returnQty <= 0 || $returnQty > $outstanding) {
                    throw new \InvalidArgumentException('Invalid return quantity for this issue');
                }

                $lockedStock = Stock::lockForUpdate()->findOrFail($issueMovement->stock_id);
                $lockedStock->quantity = (int) $lockedStock->quantity + $returnQty;
                $lockedStock->assigned = max(0, (int) $lockedStock->assigned - $returnQty);
                $lockedStock->is_assigned = ((int) $lockedStock->assigned > 0) ? 1 : 2;
                $lockedStock->save();

                ConsumableMovement::create([
                    'stock_id' => $issueMovement->stock_id,
                    'employee_id' => $issueMovement->employee_id,
                    'transection_id' => null,
                    'issue_movement_id' => $issueMovement->id,
                    'movement_type' => 'RETURN',
                    'qty' => $returnQty,
                    'movement_date' => $request->return_date,
                    'remarks' => $request->comment,
                    'created_by' => auth()->id(),
                ]);
            });
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'Failed to process consumable return'], 500);
        }

        UserLogHelper::log('update', 'Marked consumable movement as returned. Issue Movement ID: ' . $issueMovement->id);
        return response()->json(['success' => true, 'message' => 'Consumable return recorded successfully']);
    }

    public function typedConsumableProducts($id)
    {
        $type = Producttype::findOrFail($id);

        $products = DB::table('stocks')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->join('producttypes', 'producttypes.id', '=', 'stocks.producttype_id')
            ->select('stocks.id', 'stocks.asset_tag', 'stocks.service_tag', 'stocks.quantity', 'products.title', 'products.brand', 'products.model', 'producttypes.slug', 'products.is_consumable')
            ->where('stocks.producttype_id', $id)
            ->where('producttypes.asset_class', 'CONSUMABLE')
            ->where('stocks.quantity', '>', 0)
            ->get();

        return response()->json([
            'products' => $products,
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
            'return_date' => 'required|date',
            'return_quantity' => 'nullable|integer|min:1',
        ]);

        $transaction = Transection::findOrFail($id);
        $stock = Stock::with('product', 'producttype')->find($transaction->stock_id);

        $isConsumable = $stock && (strtoupper((string) ($stock->producttype->asset_class ?? '')) === 'CONSUMABLE' || (int) ($stock->product->is_consumable ?? 0) === 1);
        if ($isConsumable) {
            try {
                DB::transaction(function () use ($request, $transaction, $stock) {
                    $issuedQty = (int) $transaction->quantity;
                    $alreadyReturned = (int) ConsumableMovement::where('transection_id', $transaction->id)
                        ->where('movement_type', 'RETURN')
                        ->sum('qty');
                    $outstanding = max(0, $issuedQty - $alreadyReturned);

                    $returnQty = $request->return_quantity ? (int) $request->return_quantity : $outstanding;
                    if ($returnQty <= 0 || $returnQty > $outstanding) {
                        throw new \InvalidArgumentException('Invalid return quantity for consumable transaction');
                    }

                    $lockedStock = Stock::lockForUpdate()->findOrFail($stock->id);
                    $lockedStock->quantity = (int) $lockedStock->quantity + $returnQty;
                    $lockedStock->assigned = max(0, (int) $lockedStock->assigned - $returnQty);
                    $lockedStock->is_assigned = ((int) $lockedStock->assigned > 0) ? 1 : 2;
                    $lockedStock->save();

                    ConsumableMovement::create([
                        'stock_id' => $lockedStock->id,
                        'employee_id' => $transaction->employee_id,
                        'transection_id' => $transaction->id,
                        'movement_type' => 'RETURN',
                        'qty' => $returnQty,
                        'movement_date' => $request->return_date,
                        'remarks' => $request->comment,
                        'created_by' => auth()->id(),
                    ]);

                    if (($alreadyReturned + $returnQty) >= $issuedQty) {
                        $transaction->return_date = $request->return_date;
                        $transaction->save();
                    }
                });
            } catch (\InvalidArgumentException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            } catch (\Throwable $e) {
                report($e);
                return response()->json(['success' => false, 'message' => 'Failed to process consumable return'], 500);
            }
        } else {
            $transaction->return_date = $request->return_date;
            $transaction->save();

            // Update stock assignment status for fixed assets
            if ($stock) {
                $stock->is_assigned = 2; // Available
                $stock->save();
            }
        }

        UserLogHelper::log('update', 'Marked transaction as returned: ' . $transaction->id);

        return response()->json(['success' => true, 'message' => 'Transaction marked as returned successfully']);
    }
}
