<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Transection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;

use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->middleware('permission:employee-list|employee-create|employee-edit|employee-delete', ['only' => ['index','store']]);
         $this->middleware('permission:employee-create', ['only' => ['create','store']]);
         $this->middleware('permission:employee-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Calculate statistics
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 1)->count(),
            'inactive_employees' => Employee::where('status', 2)->count(),
            'with_assignments' => Employee::whereHas('transections', function($q) {
                $q->whereNull('return_date');
            })->count(),
            'total_departments' => Department::count(),
            'active_distributions' => Transection::whereNull('return_date')
                ->whereHas('employee')->count(),
        ];

        $departments = Department::orderBy('name')->get();

        if ($request->ajax()) {
            $department_id = $request->department_id;
            $status = $request->status;
            $assignment_status = $request->assignment_status;
            $search = $request->search['value'] ?? '';

            $query = Employee::with(['department'])
                ->select([
                    'employees.*',
                    'departments.name as department_name',
                    DB::raw('(SELECT COUNT(*) FROM transections WHERE transections.employee_id = employees.id AND transections.return_date IS NULL) as active_assignments_count'),
                    DB::raw('(SELECT COUNT(*) FROM transections WHERE transections.employee_id = employees.id) as total_assignments_count'),
                ])
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->when($department_id, fn($q) => $q->where('employees.department_id', $department_id))
                ->when($status, fn($q) => $q->where('employees.status', $status))
                ->when($assignment_status, function ($q) use ($assignment_status) {
                    if ($assignment_status == 'with_assets') {
                        $q->whereHas('transections', function($query) {
                            $query->whereNull('return_date');
                        });
                    } elseif ($assignment_status == 'no_assets') {
                        $q->whereDoesntHave('transections', function($query) {
                            $query->whereNull('return_date');
                        });
                    }
                })
                ->orderBy('employees.name');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge badge-success" style="background-color: #4CAF50; padding: 5px 10px; border-radius: 12px; font-size: 11px;">
                            <i class="material-icons" style="font-size: 12px; vertical-align: middle;">check_circle</i> Active
                        </span>';
                    } else {
                        return '<span class="badge badge-danger" style="background-color: #F44336; padding: 5px 10px; border-radius: 12px; font-size: 11px;">
                            <i class="material-icons" style="font-size: 12px; vertical-align: middle;">cancel</i> Inactive
                        </span>';
                    }
                })
                ->addColumn('assignments_info', function ($row) {
                    $active = $row->active_assignments_count;
                    $total = $row->total_assignments_count;

                    if ($active > 0) {
                        return '<span class="badge badge-primary" style="background-color: #2196F3; padding: 5px 10px; border-radius: 12px;">
                            ' . $active . ' Active
                        </span> <span class="text-muted" style="font-size: 11px;">(' . $total . ' total)</span>';
                    } else {
                        return '<span class="text-muted" style="font-size: 11px;">No active assignments (' . $total . ' total)</span>';
                    }
                })
                ->addColumn('employee_info', function ($row) {
                    return '<div style="line-height: 1.6;">
                        <strong>' . $row->name . '</strong><br>
                        <small class="text-muted">ID: ' . $row->emply_id . '</small>
                    </div>';
                })
                ->addColumn('contact_info', function ($row) {
                    $html = '<div style="line-height: 1.6; font-size: 12px;">';
                    if ($row->phone) {
                        $html .= '<i class="material-icons" style="font-size: 14px; vertical-align: middle;">phone</i> ' . $row->phone . '<br>';
                    }
                    if ($row->email) {
                        $html .= '<i class="material-icons" style="font-size: 14px; vertical-align: middle;">email</i> ' . $row->email;
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href="' . route('employees.show', $row->id) . '" class="btn btn-info btn-sm" title="View Details"><i class="material-icons">visibility</i></a> ';
                    $editBtn = '<a href="' . route('management.employees.edit', $row->id) . '" class="btn btn-primary btn-sm" title="Edit"><i class="material-icons">edit</i></a> ';

                    $statusBtn = '';
                    if ($row->status == 1) {
                        $statusBtn = '<button class="btn btn-warning btn-sm" title="Deactivate" onclick="updateStatus(' . $row->id . ')"><i class="material-icons">block</i></button>';
                    } else {
                        $statusBtn = '<button class="btn btn-success btn-sm" title="Activate" onclick="updateStatus(' . $row->id . ')"><i class="material-icons">check_circle</i></button>';
                    }

                    return $viewBtn . $editBtn . $statusBtn;
                })
                ->filterColumn('employee_info', function($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(employees.name,''), ' ', COALESCE(employees.emply_id,'')) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('contact_info', function($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(employees.phone,''), ' ', COALESCE(employees.email,'')) like ?", ["%{$keyword}%"]);
                })
                ->rawColumns(['status_badge', 'assignments_info', 'employee_info', 'contact_info', 'action'])
                ->make(true);
        }

        $employees = Employee::orderBy('name', "asc")->get();
        return view('backend.admin.employee.index', compact('employees', 'stats', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        return view('backend.admin.employee.create')->with(compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, array(
            'department' => 'required|integer',
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'date_of_join' => '',
            'phone' => '',
            'email' => '',
            'about' => '',
            'employee_id' => 'required',
            'image' => 'image',
        )
        );


        //return $request->all();
        $slug = Str::slug($request->name);

        if ($request->hasFile('image')) {

            $upload_image = $request->file('image');
            $filename = $slug . "-" . time().".".$upload_image->getClientOriginalExtension();


            $manager = new ImageManager(new Driver());
            $image = $manager->read($upload_image);
            $image->resize(400, 400);
            $image->save('images/employee/' . $filename);

        } else {
            $filename = 'no-image.png';
        }

        $employee = new Employee();

        $employee->name = $request->name;
        $employee->department_id = $request->department;
        $employee->designation = $request->designation;
        $employee->emply_id = $request->employee_id;
        $employee->date_of_join = $request->date_of_join;
        $employee->phone = $request->phone;
        $employee->status = 1;
        $employee->email = $request->email;
        $employee->about = $request->about;
        $employee->image = $filename;
        $employee->save();

        UserLogHelper::log('create', 'Created Employee: '. $employee->name ."Employee ID: ". $employee->emply_id);

        Toastr::success(' Succesfully Saved ', 'Success');
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        return view('backend.admin.employee.show')->with(compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departments = Department::all();
        $employee = Employee::find($id);
        return view('backend.admin.employee.edit')->with(compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'department' => 'required|integer',
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'date_of_join' => '',
            'date_of_resign' => '',
            'phone' => '',
            'email' => '',
            'about' => '',
            'image' => 'image',
        )
        );


        $transections = false;//Transection::where('employee_id', '=', $id )->exists();

        if ($transections) {
            Toastr::error('Update Resticted ', 'Error');
        } else {


            $employee = Employee::find($id);
            $slug = Str::slug($request->name);

            if ($request->hasFile('image')) {

                $upload_image = $request->file('image');
                $filename = $slug . "-" . time().".".$upload_image->getClientOriginalExtension();


                $manager = new ImageManager(new Driver());
                $image = $manager->read($upload_image);
                $image->resize(400, 400);
                $image->save('images/employee/' . $filename);

                if (file_exists('images/employee/' . $employee->image)) {
                    unlink('images/employee/' . $employee->image);
                }

            } else {
                $filename = $employee->image;
            }
            $employee->name = $request->name;
            $employee->department_id = $request->department;
            $employee->designation = $request->designation;
            $employee->date_of_join = $request->date_of_join;
            $employee->resign_date = $request->date_of_resign;
            $employee->phone = $request->phone;
            $employee->status = 1;
            $employee->email = $request->email;
            $employee->about = $request->about;
            $employee->image = $filename;
            $employee->save();
            UserLogHelper::log('update', 'Updated Employee: '. $employee->name ." Employee ID: ". $employee->emply_id);

            Toastr::success(' Succesfully Saved ', 'Success');
        }

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id)
    {
        $employee = Employee::find($id);

        if ($employee->status == 1) {
            $employee->status = 2;
        } else {
            $employee->status = 1;
        }

        $employee->save();
        UserLogHelper::log('disable', 'Updated Employee Status: '. $employee->name ." Employee ID: ". $employee->emply_id);
        Toastr::success(' Status Updated ', 'Success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        //
    }
}
