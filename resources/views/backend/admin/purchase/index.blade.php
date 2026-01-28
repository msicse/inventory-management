@extends('layouts.backend.app')

@section('title', 'Admin | Purchases')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td {
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

        .success-row {
            background-color: #d4edda !important;
            transition: background-color 0.3s;
        }

        .success-row:hover {
            background-color: #c3e6cb !important;
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

        /* Quick Filter Buttons */
        .quick-filters {
            margin-bottom: 15px;
        }

        .quick-filters .btn {
            margin-right: 5px;
            margin-bottom: 5px;
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
        <!-- Page Title & Action Button -->
        <div class="row clearfix">
            <div class="col-lg-12">

                <div class="card">
                    <div class="header">
                        <h2 class="text-uppercase">
                            <i class="material-icons" style="vertical-align: middle;">shopping_cart</i>
                            Purchases MANAGEMENT
                            <span class="badge "></span>
                        </h2>
                        <div>
                            @can("purchase-create")
                                <a href="{{ route('purchases.create') }}" class="btn btn-primary waves-effect pull-right"
                                    style="margin-bottom:10px;">
                                    <i class="material-icons">add</i>
                                    <span>Add New Purchase</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Banner -->
        @if($stats['pending_purchases'] > 0)
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="material-icons" style="vertical-align: middle;">warning</i> Attention Required:</strong>
                    <span class="badge" style="background: #ff9800; color: white; margin: 0 5px;">{{ $stats['pending_purchases'] }} purchases awaiting approval</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="material-icons icon">shopping_cart</i>
                    <div class="number">{{ $stats['total_purchases'] }}</div>
                    <div class="label">Total Purchases</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white;">
                    <i class="material-icons icon">check_circle</i>
                    <div class="number">{{ $stats['approved_purchases'] }}</div>
                    <div class="label">Approved</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white;">
                    <i class="material-icons icon">schedule</i>
                    <div class="number">{{ $stats['pending_purchases'] }}</div>
                    <div class="label">Pending</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white;">
                    <i class="material-icons icon">account_balance_wallet</i>
                    <div class="number">{{ number_format($stats['total_value']/1000, 0) }}K</div>
                    <div class="label">Total Value</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); color: white;">
                    <i class="material-icons icon">calendar_today</i>
                    <div class="number">{{ $stats['this_month_purchases'] }}</div>
                    <div class="label">This Month</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); color: white;">
                    <i class="material-icons icon">trending_up</i>
                    <div class="number">{{ number_format($stats['this_month_value']/1000, 0) }}K</div>
                    <div class="label">Month Value</div>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="quick-filters">
                            <button type="button" class="btn btn-primary waves-effect quick-filter" data-filter="today">
                                <i class="material-icons">today</i> Today
                            </button>
                            <button type="button" class="btn btn-info waves-effect quick-filter" data-filter="week">
                                <i class="material-icons">date_range</i> This Week
                            </button>
                            <button type="button" class="btn btn-success waves-effect quick-filter" data-filter="month">
                                <i class="material-icons">calendar_month</i> This Month
                            </button>
                            <button type="button" class="btn btn-warning waves-effect quick-filter" data-filter="pending">
                                <i class="material-icons">schedule</i> Pending Only
                            </button>
                            <button type="button" class="btn btn-default waves-effect quick-filter" data-filter="approved">
                                <i class="material-icons">check_circle</i> Approved Only
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters Panel -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <i class="material-icons" style="vertical-align: middle;">filter_list</i>
                            ADVANCED FILTERS
                            <span class="filter-badge" id="activeFiltersCount" style="display: none;">0</span>
                        </h2>
                    </div>
                    <div class="body filters-panel">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select id="supplier" class="form-control">
                                        <option value="">All Suppliers</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->company }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="approved" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Pending</option>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Min Price</label>
                                    <input type="number" id="min_price" class="form-control" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Max Price</label>
                                    <input type="number" id="max_price" class="form-control" placeholder="999999">
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
                            All Purchases
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="purchasesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier</th>
                                        <th>Contact</th>
                                        <th>Phone</th>
                                        <th>Invoice No.</th>
                                        <th>Challan No.</th>
                                        <th>Total Price</th>
                                        <th>Purchase Date</th>
                                        <th>Status</th>
                                        <th>Progress</th>
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
            let table = $('#purchasesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchases.index') }}',
                    data: function (d) {
                        d.supplier_id = $('#supplier').val();
                        d.approved = $('#approved').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.min_price = $('#min_price').val();
                        d.max_price = $('#max_price').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'supplier_company', name: 'suppliers.company' },
                    { data: 'supplier_name', name: 'suppliers.name' },
                    { data: 'supplier_phone', name: 'suppliers.phone' },
                    { data: 'invoice_no', name: 'purchases.invoice_no' },
                    { data: 'challan_no', name: 'purchases.challan_no' },
                    { data: 'total_price', name: 'purchases.total_price' },
                    { data: 'purchase_date', name: 'purchases.purchase_date' },
                    { data: 'status_badge', name: 'is_stocked', orderable: false },
                    { data: 'approval_progress', name: 'approval_progress', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.is_stocked == 1) {
                        $(row).addClass('success-row');
                    } else if (data.days_since_purchase > 7) {
                        $(row).addClass('warning-row');
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
                            columns: [0,1,2,4,5,6,7]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,4,5,6,7]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,4,5,6,7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn-sm',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,4,5,6,7]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [0,1,2,4,5,6,7]
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });

            // Initialize Select2
            $('#supplier').select2({ placeholder: 'Select Supplier', allowClear: true });
            $('#approved').select2({ placeholder: 'Select Status', allowClear: true });

            // Update active filters count
            function updateActiveFilters() {
                let count = 0;
                if ($('#supplier').val()) count++;
                if ($('#approved').val()) count++;
                if ($('#date_from').val()) count++;
                if ($('#date_to').val()) count++;
                if ($('#min_price').val()) count++;
                if ($('#max_price').val()) count++;

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
                $('#supplier').val('').trigger('change');
                $('#approved').val('').trigger('change');
                $('#date_from').val('');
                $('#date_to').val('');
                $('#min_price').val('');
                $('#max_price').val('');
                updateActiveFilters();
                table.ajax.reload();
            });

            // Quick Filters
            $('.quick-filter').on('click', function() {
                let filter = $(this).data('filter');
                let today = new Date();

                // Clear all filters first
                $('#supplier').val('').trigger('change');
                $('#approved').val('').trigger('change');
                $('#date_from').val('');
                $('#date_to').val('');
                $('#min_price').val('');
                $('#max_price').val('');

                if (filter === 'today') {
                    let dateStr = today.toISOString().split('T')[0];
                    $('#date_from').val(dateStr);
                    $('#date_to').val(dateStr);
                } else if (filter === 'week') {
                    let weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    $('#date_from').val(weekAgo.toISOString().split('T')[0]);
                    $('#date_to').val(today.toISOString().split('T')[0]);
                } else if (filter === 'month') {
                    let firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    $('#date_from').val(firstDay.toISOString().split('T')[0]);
                    $('#date_to').val(today.toISOString().split('T')[0]);
                } else if (filter === 'pending') {
                    $('#approved').val('2').trigger('change');
                } else if (filter === 'approved') {
                    $('#approved').val('1').trigger('change');
                }

                updateActiveFilters();
                table.ajax.reload();
            });
        });
    </script>
@endpush
