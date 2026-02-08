@extends('layouts.backend.app')

@section('title','Distribution')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
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

        /* Row Highlighting */
        .warning-row {
            background-color: #fff3cd !important;
            transition: background-color 0.3s;
        }

        .warning-row:hover {
            background-color: #ffe69c !important;
        }

        .danger-row {
            background-color: #f8d7da !important;
            transition: background-color 0.3s;
        }

        .danger-row:hover {
            background-color: #f1b0b7 !important;
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
    </style>
@endpush

@section('content')
<div class="container-fluid">
     <div class="row clearfix">
            <div class="col-lg-12">

                <div class="card">
                    <div class="header">
                        <h2 class="text-uppercase">
                            <i class="material-icons" style="vertical-align: middle;">compare_arrows</i>
                           Distributions MANAGEMENT
                            <span class="badge "></span>
                        </h2>
                        <div>
                            @can("distribution-create")
                                <a href="{{ route('transections.create') }}" class="btn btn-primary waves-effect pull-right"
                                    style="margin-bottom:10px;">
                                    <i class="material-icons">add</i>
                                    <span>Add New Distribution</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Alert Banner -->
    @if($stats['overdue_items'] > 0)
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><i class="material-icons" style="vertical-align: middle;">warning</i> Attention Required:</strong>
                <span class="badge" style="background: #f44336; color: white; margin: 0 5px;">{{ $stats['overdue_items'] }} overdue items (not returned for >30 days)</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row clearfix">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="material-icons icon">swap_horiz</i>
                <div class="number">{{ $stats['total_transactions'] }}</div>
                <div class="label">Total Transactions</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white;">
                <i class="material-icons icon">assignment</i>
                <div class="number">{{ $stats['active_assignments'] }}</div>
                <div class="label">Active Assignments</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white;">
                <i class="material-icons icon">assignment_turned_in</i>
                <div class="number">{{ $stats['returned_items'] }}</div>
                <div class="label">Returned Items</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white;">
                <i class="material-icons icon">schedule</i>
                <div class="number">{{ $stats['overdue_items'] }}</div>
                <div class="label">Overdue</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); color: white;">
                <i class="material-icons icon">people</i>
                <div class="number">{{ $stats['unique_employees'] }}</div>
                <div class="label">Active Employees</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white;">
                <i class="material-icons icon">inventory</i>
                <div class="number">{{ $stats['total_items_out'] }}</div>
                <div class="label">Items Out</div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters Panel -->
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header" style="cursor: pointer;" onclick="$('#filtersBody').slideToggle();">
                    <h2>
                        <i class="material-icons" style="vertical-align: middle;">filter_list</i>
                        ADVANCED FILTERS
                        <span class="filter-badge" id="activeFiltersCount" style="display: none;">0</span>
                    </h2>
                </div>
                <div class="body filters-panel" id="filtersBody">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Employee</label>
                                <select id="employee" class="form-control">
                                    <option value="">All Employees</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }} - {{ sprintf('%03d', $emp->emply_id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Department</label>
                                <select id="department" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Product Type</label>
                                <select id="product_type" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="returned">Returned</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" id="date_from" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" id="date_to" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="applyFilters" class="btn btn-primary waves-effect">
                                <i class="material-icons">search</i> Apply Filters
                            </button>
                            <button type="button" id="clearFilters" class="btn btn-warning waves-effect">
                                <i class="material-icons">clear</i> Clear Filters
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
                        Distribution List
                    </h2>
                    @can('distribution-create')
                    <a href="{{ route('transections.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
                        <i class="material-icons">add</i>
                        <span>Add New Distribution</span>
                    </a>
                    @endcan
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="transectionsTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Asset Tag</th>
                                    <th>Serial No</th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Condition</th>
                                    <th>Issue Date</th>
                                    <th>Return Date</th>
                                    <th>Days</th>
                                    <th>Qty.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>

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
            // Initialize DataTable
            let table = $('#transectionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('transections.index') }}',
                    data: function (d) {
                        d.employee_id = $('#employee').val();
                        d.department_id = $('#department').val();
                        d.product_type = $('#product_type').val();
                        d.status = $('#status').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'product_name', name: 'products.title' },
                    { data: 'product_type_name', name: 'producttypes.name' },
                    { data: 'asset_tag', name: 'stocks.asset_tag' },
                    { data: 'service_tag', name: 'stocks.service_tag' },
                    { data: 'employee_info', name: 'employees.name' },
                    { data: 'department_name', name: 'departments.name' },
                    { data: 'status_badge', name: 'transaction_status', orderable: false },
                    { data: 'condition_badge', name: 'stocks.asset_condition', orderable: false },
                    { data: 'issued_date', name: 'transections.issued_date' },
                    { data: 'return_date', name: 'transections.return_date', orderable: false },
                    { data: 'days_with_asset', name: 'days_with_asset' },
                    { data: 'quantity', name: 'transections.quantity' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.transaction_status === 'Overdue') {
                        $(row).addClass('danger-row');
                    }
                },
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,3,4,5,6,9,10,12]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,3,4,5,6,9,10,12]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,3,4,5,6,9,10,12]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn-sm',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,3,4,5,6,9,10,12]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,3,4,5,6,9,10,12]
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });

            // Initialize Select2
            $('#employee').select2({ placeholder: 'Select Employee', allowClear: true });
            $('#department').select2({ placeholder: 'Select Department', allowClear: true });
            $('#product_type').select2({ placeholder: 'Select Type', allowClear: true });
            $('#status').select2({ placeholder: 'Select Status', allowClear: true });

            // Update active filters count
            function updateActiveFilters() {
                let count = 0;
                if ($('#employee').val()) count++;
                if ($('#department').val()) count++;
                if ($('#product_type').val()) count++;
                if ($('#status').val()) count++;
                if ($('#date_from').val()) count++;
                if ($('#date_to').val()) count++;

                if (count > 0) {
                    $('#activeFiltersCount').text(count).show();
                } else {
                    $('#activeFiltersCount').hide();
                }
            }

            // Apply Filters
            $('#applyFilters').on('click', function() {
                updateActiveFilters();
                table.ajax.reload();
            });

            // Clear Filters
            $('#clearFilters').on('click', function() {
                $('#employee').val('').trigger('change');
                $('#department').val('').trigger('change');
                $('#product_type').val('').trigger('change');
                $('#status').val('').trigger('change');
                $('#date_from').val('');
                $('#date_to').val('');
                updateActiveFilters();
                table.ajax.reload();
            });

            // Handle return date input
            $(document).on('change', '.return-date-input', function() {
                let transactionId = $(this).data('id');
                let returnDate = $(this).val();

                if (!returnDate) return;

                if (confirm('Are you sure you want to mark this item as returned?')) {
                    $.ajax({
                        url: '/transections/mark-returned/' + transactionId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            return_date: returnDate
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            toastr.error('Error marking as returned');
                        }
                    });
                }
            });

            // Handle mark as returned button
            $(document).on('click', '.mark-returned', function() {
                let transactionId = $(this).data('id');
                let today = new Date().toISOString().split('T')[0];

                if (confirm('Mark this item as returned today?')) {
                    $.ajax({
                        url: '/transections/mark-returned/' + transactionId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            return_date: today
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            toastr.error('Error marking as returned');
                        }
                    });
                }
            });
        });
    </script>
@endpush
