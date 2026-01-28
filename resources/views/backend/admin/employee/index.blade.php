@extends('layouts.backend.app')

@section('title','Admin | Employees')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td{
            vertical-align: middle !important;
        }

        /* Statistics Cards */
        .stat-card {
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .stat-card .icon {
            font-size: 48px;
            opacity: 0.9;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
            margin: 10px 0 5px 0;
        }

        .stat-card .label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            opacity: 0.9;
            line-height: 1.2;
        }

        /* Filters Panel */
        .filters-panel {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-badge {
            background: #667eea;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 5px;
        }

        /* DataTable Buttons */
        .dt-buttons .btn {
            margin-right: 5px !important;
            margin-bottom: 5px !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }

            .stat-card .icon {
                font-size: 36px;
            }

            .stat-card .number {
                font-size: 24px;
            }
        }

        .badge {
            display: inline-flex;
            align-items: center;
        }

        .badge .material-icons {
            font-size: 14px;
            margin-right: 3px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">

    <!-- Page Title & Action Button -->
        <div class="row clearfix">
            <div class="col-lg-12">

                <div class="card">
                    <div class="header">
                        <h2 class="text-uppercase">
                            <i class="material-icons" style="vertical-align: middle;">people</i>
                           EMPLOYEE MANAGEMENT
                            <span class="badge "></span>
                        </h2>
                        <div>
                            @can("employee-create")
                                <a href="{{ route('employees.create') }}" class="btn btn-primary waves-effect pull-right"
                                    style="margin-bottom:10px;">
                                    <i class="material-icons">add</i>
                                    <span>Add New Employee</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Statistics Cards -->
    <div class="row clearfix">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="material-icons icon">people</i>
                <div class="number">{{ $stats['total_employees'] }}</div>
                <div class="label">Total Employees</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                <i class="material-icons icon">check_circle</i>
                <div class="number">{{ $stats['active_employees'] }}</div>
                <div class="label">Active</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #F44336 0%, #E91E63 100%); color: white;">
                <i class="material-icons icon">cancel</i>
                <div class="number">{{ $stats['inactive_employees'] }}</div>
                <div class="label">Inactive</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #2196F3 0%, #00BCD4 100%); color: white;">
                <i class="material-icons icon">assignment</i>
                <div class="number">{{ $stats['with_assignments'] }}</div>
                <div class="label">With Assets</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #FF9800 0%, #FF5722 100%); color: white;">
                <i class="material-icons icon">business</i>
                <div class="number">{{ $stats['total_departments'] }}</div>
                <div class="label">Departments</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%); color: white;">
                <i class="material-icons icon">trending_up</i>
                <div class="number">{{ $stats['active_distributions'] }}</div>
                <div class="label">Active Items Out</div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card filters-panel">
                <div class="header" style="background: transparent; margin-bottom: 15px;">
                    <h2 style="color: #333; font-size: 16px; font-weight: 600;">
                        <i class="material-icons" style="vertical-align: middle;">filter_list</i>
                        Advanced Filters
                        <span class="filter-badge" id="activeFilterCount" style="display: none;">0</span>
                    </h2>
                </div>
                <div class="body" style="padding-top: 0;">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Department</label>
                            <select class="form-control" id="filterDepartment">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Assignment Status</label>
                            <select class="form-control" id="filterAssignmentStatus">
                                <option value="">All</option>
                                <option value="with_assets">With Assets</option>
                                <option value="no_assets">No Assets</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="padding-top: 20px;">
                            <button type="button" id="applyFilters" class="btn btn-primary waves-effect">
                                <i class="material-icons">search</i> Apply Filters
                            </button>
                            <button type="button" id="clearFilters" class="btn btn-warning waves-effect">
                                <i class="material-icons">clear</i> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Employees
                        <small>Complete employee directory</small>
                    </h2>
                </div>
                <div class="body table-responsive">
                    <table id="employeesTable" class="table table-bordered table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Assignments</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>


{{-- Delete Modal --}}
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog">
        <form class="delete_form" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Employee Status </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure to update the employee status ?</strong>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@push('js')
    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#filterDepartment, #filterStatus, #filterAssignmentStatus').select2({
                placeholder: 'Select...',
                allowClear: true
            });

            // Initialize DataTable
            var table = $('#employeesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('employees.index') }}",
                    data: function(d) {
                        d.department_id = $('#filterDepartment').val();
                        d.status = $('#filterStatus').val();
                        d.assignment_status = $('#filterAssignmentStatus').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'employee_info', name: 'employees.name' },
                    { data: 'department_name', name: 'departments.name' },
                    { data: 'designation', name: 'employees.designation' },
                    { data: 'contact_info', name: 'employees.phone', orderable: false },
                    { data: 'status_badge', name: 'employees.status' },
                    { data: 'assignments_info', name: 'active_assignments_count', orderable: false, searchable: false },
                    {
                        data: 'image',
                        name: 'employees.image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<img src="/images/employee/' + data + '" style="height:60px; width:70px; object-fit: cover; border-radius: 4px;" />';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6]
                        }
                    }
                ],
                pageLength: 25,
                order: [[1, 'asc']],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                }
            });

            // Apply Filters
            $('#applyFilters').on('click', function() {
                table.ajax.reload();
                updateFilterBadge();
            });

            // Clear Filters
            $('#clearFilters').on('click', function() {
                $('#filterDepartment, #filterStatus, #filterAssignmentStatus').val('').trigger('change');
                table.ajax.reload();
                updateFilterBadge();
            });

            // Update filter count badge
            function updateFilterBadge() {
                let count = 0;
                if ($('#filterDepartment').val()) count++;
                if ($('#filterStatus').val()) count++;
                if ($('#filterAssignmentStatus').val()) count++;

                if (count > 0) {
                    $('#activeFilterCount').text(count).show();
                } else {
                    $('#activeFilterCount').hide();
                }
            }

            // Delete modal handler
            $(document).on('click', '.delete', function() {
                var data_id = $(this).data('delete-id');
                var url = location.origin + '/employees/status/' + data_id;
                $('.delete_form').attr('action', url);
            });
        });

        // Update Status Function
        function updateStatus(id) {
            if (confirm('Are you sure you want to update the employee status?')) {
                $.ajax({
                    url: '/employees/status/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#employeesTable').DataTable().ajax.reload();
                        toastr.success('Status updated successfully');
                    },
                    error: function(xhr) {
                        toastr.error('Error updating status');
                    }
                });
            }
        }
    </script>

@endpush
