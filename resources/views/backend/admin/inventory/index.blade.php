@extends('layouts.backend.app')

@section('title', 'Inventories')

@push('css')
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
        rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td {
            vertical-align: middle !important;
        }

        .custom_width {
            width: 100px;
        }

        .custom_width2 {
            width: 140px;
        }

        li a span.text {
            padding-left: 30px !important;
        }

        .bs-searchbox input {
            padding-left: 20px !important;
        }

        .bootstrap-select .dropdown-toggle:focus {
            outline: 0 dotted #333333 !important;
            outline: 0 auto -webkit-focus-ring-color !important;
            outline-offset: 0 !important;

        }

        .form-group {
            margin-bottom: 20px !important;
        }

        .body {
            min-height: 110px;
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        /* Status Badges */
        .badge-assigned {
            background: #4caf50;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-available {
            background: #ff9800;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .warranty-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .warranty-good { background: #4caf50; }
        .warranty-warning { background: #ff9800; }
        .warranty-expired { background: #f44336; }

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

            .filters-panel .col-md-2 {
                margin-bottom: 10px;
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
                            <i class="material-icons" style="vertical-align: middle;">inventory_2</i>
                            Inventories MANAGEMENT
                            <span class="badge "></span>
                        </h2>
                        <div>
                            @can("purchase-create")
                                <a href="{{ route('dashboard') }}" class="btn btn-primary waves-effect pull-right"
                                    style="margin-bottom:10px;">
                                    <i class="material-icons">dashboard</i>
                                    <span>Dashboard</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick Summary Alert -->
        @if($stats['pending_tags'] > 0 || $stats['warranty_expiring'] > 0 || $stats['damaged_items'] > 0)
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="material-icons" style="vertical-align: middle;">warning</i> Attention Required:</strong>
                    @if($stats['pending_tags'] > 0)
                        <span class="badge" style="background: #f44336; color: white; margin: 0 5px;">{{ $stats['pending_tags'] }} items need asset tags</span>
                    @endif
                    @if($stats['warranty_expiring'] > 0)
                        <span class="badge" style="background: #9c27b0; color: white; margin: 0 5px;">{{ $stats['warranty_expiring'] }} warranties expiring soon</span>
                    @endif
                    @if($stats['damaged_items'] > 0)
                        <span class="badge" style="background: #00bcd4; color: white; margin: 0 5px;">{{ $stats['damaged_items'] }} damaged items</span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="material-icons icon">inventory_2</i>
                    <div class="number">{{ $stats['total_items'] }}</div>
                    <div class="label">Total Items</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white;">
                    <i class="material-icons icon">assignment_ind</i>
                    <div class="number">{{ $stats['assigned'] }}</div>
                    <div class="label">Assigned</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white;">
                    <i class="material-icons icon">store</i>
                    <div class="number">{{ $stats['available'] }}</div>
                    <div class="label">Available</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white;">
                    <i class="material-icons icon">local_offer</i>
                    <div class="number">{{ $stats['pending_tags'] }}</div>
                    <div class="label">Pending Tags</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); color: white;">
                    <i class="material-icons icon">warning</i>
                    <div class="number">{{ $stats['warranty_expiring'] }}</div>
                    <div class="label">Warranty Soon</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); color: white;">
                    <i class="material-icons icon">build</i>
                    <div class="number">{{ $stats['damaged_items'] }}</div>
                    <div class="label">Damaged</div>
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
                                    <label>Product Type</label>
                                    <select id="type" class="form-control">
                                        <option value="">All Types</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Condition</label>
                                    <select id="condition" class="form-control">
                                        <option value="">All Conditions</option>
                                        <option value="Good">Good</option>
                                        <option value="Fair">Fair</option>
                                        <option value="Poor">Poor</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Obsolete">Obsolete</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select id="store" class="form-control">
                                        <option value="">All Stores</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                    <label>Assignment Status</label>
                                    <select id="assignment_status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="1">Assigned</option>
                                        <option value="2">Available</option>
                                    </select>
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
                            Inventories
                        </h2>
                        <div class="pull-right">
                            <button type="button" id="bulk-qr-label-btn" class="btn btn-success waves-effect" style="display:none;" onclick="printSelectedQrLabels()">
                                <i class="material-icons">qr_code_2</i>
                                <span>Print Selected QR Labels</span>
                            </button>
                            {{-- <button type="button" id="bulk-combo-btn" class="btn btn-primary waves-effect" style="display:none;" onclick="printSelectedComboLabels()">
                                <i class="material-icons">view_module</i>
                                <span>Print Selected Combo Labels</span>
                            </button> --}}
                        </div>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="stockTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all" class="filled-in" />
                                            <label for="select-all"></label>
                                        </th>
                                        <th>SL</th>
                                        <th>Type</th>
                                        <th>Product</th>
                                        <th>SN / IMEI </th>
                                        <th>Asset Tag</th>
                                        <th>Condition</th>
                                        <th title="Quantity">Qty</th>
                                        <th>Supplier</th>
                                        <th>Purchase Date</th>
                                        <th>User/Location</th>
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

    <!-- Update  -->
    <div class="modal fade" id="popupModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="edit-form" method="post" id="editForm">

                    <input type="hidden" id="inventoryId">

                    <div class="modal-header custom-modal">
                        <h4 class="modal-title" id="defaultModalLabel">Update Inventory</h4>
                    </div>
                    <div class="modal-body">
                        <div id="errorMessages"></div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group form-float">
                                    <select name="updateCondition" id="updateCondition"
                                        class="form-control form-control-sm show-tick" data-live-search="true">
                                        <option value="">Select Condition</option>
                                        <option value="good">Good</option>
                                        <option value="obsolete">Obsolete </option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <input type="text" class="form-control" name="serial_no" id="serial_no" value=""
                                        placeholder="Serial No / IMEI">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group form-float">
                                    <select id="updateStore" name="updateStore" class="form-control">
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group form-float">
                                    <input type="text" class="form-control" name="asset_tag" id="asset_tag" value=""
                                        placeholder="Asset Tag">
                                </div>
                                <div class="form-group form-float">
                                    <select id="updateEmployee" class="form-control form-control-sm show-tick"
                                        data-live-search="true">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->name . ' - ' . $employee->emply_id }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect">Update</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </form>
            </div>
        </div>
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
        $(document).ready(function () {

            $('#updateEmployee').select2({
                width: '100%',
                dropdownParent: $('#popupModal'),
            });

            let exportOptions = {
                columns: ':visible', // Export only visible columns
                modifier: {
                    search: 'applied',
                    order: 'applied',
                    page: 'all' // Export all pages, not just the first one
                }
            };

            // Initialize DataTable
            let table = $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('inventories.index') }}',
                    data: function (d) {
                        // Pass custom filter data to the server
                        d.product_type = $('#type').val();
                        d.condition = $('#condition').val();
                        d.product_id = $('#store').val();
                        d.store = $('#store').val();
                        d.supplier = $('#supplier').val();
                        d.department = $('#department').val();
                        d.assignment_status = $('#assignment_status').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'product_type', name: 'producttypes.name', searchable: false },
                    { data: 'title', name: 'products.title' },
                    { data: 'service_tag', name: 'service_tag' },
                    {
                        data: 'asset_tag',
                        name: 'asset_tag',
                        render: function(data, type, row) {
                            if (!data || data === '') {
                                return '<span class="badge-assigned" style="background: #f44336;">Missing Tag</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'asset_condition',
                        name: 'asset_condition',
                        render: function(data, type, row) {
                            let badgeClass = 'badge-available';
                            if (data === 'Good') badgeClass = 'badge-assigned';
                            if (data === 'Damaged' || data === 'Poor') badgeClass = 'badge-assigned';

                            let colors = {
                                'Good': '#4caf50',
                                'Fair': '#ff9800',
                                'Poor': '#f44336',
                                'Damaged': '#d32f2f',
                                'Obsolete': '#9e9e9e'
                            };

                            return '<span class="badge-assigned" style="background: ' + (colors[data] || '#2196f3') + ';">' + data + '</span>';
                        }
                    },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'supplier_company', name: 'suppliers.company' },
                    {
                        data: 'purchase_date',
                        name: 'stocks.purchase_date',
                        title: 'Purchase Date',
                        render: function (data, type, row) {
                            if (data) {
                                let date = new Date(data);
                                let formatted = date.toLocaleDateString('en-GB');

                                // Add warranty indicator
                                if (row.days_to_expire !== undefined && row.days_to_expire !== null) {
                                    let indicator = '';
                                    if (row.days_to_expire < 0) {
                                        indicator = '<span class="warranty-indicator warranty-expired" title="Warranty Expired"></span>';
                                    } else if (row.days_to_expire <= 30) {
                                        indicator = '<span class="warranty-indicator warranty-warning" title="Expiring in ' + row.days_to_expire + ' days"></span>';
                                    } else {
                                        indicator = '<span class="warranty-indicator warranty-good" title="Warranty Valid"></span>';
                                    }
                                    return indicator + formatted;
                                }

                                return formatted;
                            }
                            return '';
                        }
                    },
                    {
                        data: 'assigned_to',
                        name: 'employees.name',
                        render: function(data, type, row) {
                            if (data && data !== '-') {
                                return '<span style="color: #000;">' + data + '</span>';
                            }
                            return '<span class="badge-available">Available</span>';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                createdRow: function(row, data, dataIndex) {
                    // Row highlighting based on conditions
                    if (!data.asset_tag || data.asset_tag === '') {
                        $(row).addClass('warning-row');
                    }
                    if (data.asset_condition === 'Damaged' || data.asset_condition === 'Poor') {
                        $(row).addClass('danger-row');
                    }
                    if (data.days_to_expire !== undefined && data.days_to_expire !== null &&
                        data.days_to_expire >= 0 && data.days_to_expire <= 30) {
                        $(row).css('border-left', '4px solid #ff9800');
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
                            columns: [1,2,3,4,5,6,7,8,9,10] // Exclude checkbox and action columns
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [1,2,3,4,5,6,7,8,9,10]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [1,2,3,4,5,6,7,8,9,10]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn-sm',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [1,2,3,4,5,6,7,8,9,10]
                        },
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 11;
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';

                            doc.content[1].layout = {
                                tableWidth: '100%',
                            };
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn-sm',
                        exportOptions: {
                            modifier: { page: 'all' },
                            columns: [1,2,3,4,5,6,7,8,9,10]
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });

            // Custom Filters Trigger Table Reload with Active Count
            function updateActiveFilters() {
                let count = 0;
                if ($('#type').val()) count++;
                if ($('#condition').val()) count++;
                if ($('#store').val()) count++;
                if ($('#supplier').val()) count++;
                if ($('#department').val()) count++;
                if ($('#assignment_status').val()) count++;

                if (count > 0) {
                    $('#activeFiltersCount').text(count).show();
                } else {
                    $('#activeFiltersCount').hide();
                }
            }

            // Apply Filters Button
            $('#applyFilters').on('click', function() {
                updateActiveFilters();
                table.ajax.reload();
            });

            // Clear Filters Button
            $('#clearFilters').on('click', function() {
                $('#type').val('').trigger('change');
                $('#condition').val('').trigger('change');
                $('#store').val('').trigger('change');
                $('#supplier').val('').trigger('change');
                $('#department').val('').trigger('change');
                $('#assignment_status').val('').trigger('change');
                updateActiveFilters();
                table.ajax.reload();
            });

            $('#product_id').select2({
                width: '100%',
            });

            // Initialize Select2 for all filters
            $('#type').select2({ placeholder: 'Select Type', allowClear: true });
            $('#store').select2({ placeholder: 'Select Store', allowClear: true });
            $('#supplier').select2({ placeholder: 'Select Supplier', allowClear: true });
            $('#condition').select2({ placeholder: 'Select Condition', allowClear: true });
            $('#department').select2({ placeholder: 'Select Department', allowClear: true });
            $('#assignment_status').select2({ placeholder: 'Select Status', allowClear: true });

            $(document).on('click', '.open-popup', function () {
                let inventoryId = $(this).data('id');
                $('#serial_no').val($(this).data('service-tag'));
                $('#asset_tag').val($(this).data('asset-tag'));
                $('#updateStore').val($(this).data('store-id'));
                $('#updateCondition').val($(this).data('condition'));
                //$('#updateEmployee').val($(this).data('assigned-id'));

                let assignedId = $(this).data('assigned-id');
                $('#updateEmployee').val(assignedId).trigger('change');

                $('#popupModal').modal('show');
                $('#inventoryId').val(inventoryId);
            })

            $('#editForm').on("submit", function (e) {
                e.preventDefault();

                let inventoryId = $('#inventoryId').val();
                let updateCondition = $('#updateCondition').val();
                let updateStore = $('#updateStore').val();
                let updateEmployee = $('#updateEmployee').val();
                let updateSerial = $('#serial_no').val();
                let updateAssetTag = $('#asset_tag').val();


                if (!updateCondition && !updateStore && !updateSerial && !updateAssetTag && !updateEmployee) {
                    $('#errorMessages').html('<div class="alert alert-danger">Anyone field is required.</div>');
                    return;
                }

                // Clear previous error messages
                $('#errorMessages').html('');

                $.ajax({
                    url: `/inventories/${inventoryId}`,
                    type: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        store_id: updateStore,
                        condition: updateCondition,
                        serial_no: updateSerial,
                        asset_tag: updateAssetTag,
                        employee_id: updateEmployee,
                    },
                    success: function (response) {
                        console.log(response);
                        $('#editForm')[0].reset();
                        $('#popupModal').modal('hide');
                        $('#stockTable').DataTable().ajax.reload();
                        toastr.success(response.message);

                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '<div class="alert alert-danger"><ul>';
                            $.each(errors, function (key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                            });
                            errorHtml += '</ul></div>';
                            $('#errorMessages').html(errorHtml);
                        } else {
                            // Handle server errors

                            alert(xhr.responseJSON.error);
                        }
                    }
                });
            });
        });

        // Checkbox functionality - persist selections across pages
        let selectedStockIds = new Set();

        $(document).ready(function() {

            // Restore checkboxes after each DataTable draw (page change, sort, filter)
            $('#stockTable').on('draw.dt', function() {
                $('.stock-checkbox').each(function() {
                    let id = $(this).val();
                    $(this).prop('checked', selectedStockIds.has(id));
                });
                updateSelectAllState();
                updateBulkBtn();
            });

            // Select All checkbox - only affects current page
            $('#select-all').on('change', function() {
                let isChecked = $(this).is(':checked');
                $('.stock-checkbox').each(function() {
                    $(this).prop('checked', isChecked);
                    let id = $(this).val();
                    if (isChecked) {
                        selectedStockIds.add(id);
                    } else {
                        selectedStockIds.delete(id);
                    }
                });
                updateBulkBtn();
            });

            // Individual checkbox change
            $(document).on('change', '.stock-checkbox', function() {
                let id = $(this).val();
                if ($(this).is(':checked')) {
                    selectedStockIds.add(id);
                } else {
                    selectedStockIds.delete(id);
                }
                updateSelectAllState();
                updateBulkBtn();
            });

            function updateSelectAllState() {
                let totalCheckboxes = $('.stock-checkbox').length;
                let checkedCheckboxes = $('.stock-checkbox:checked').length;
                $('#select-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
            }

            function updateBulkBtn() {
                if (selectedStockIds.size > 0) {
                    $('#bulk-qr-label-btn').show().find('span').text('Print Selected QR Labels (' + selectedStockIds.size + ')');
                    $('#bulk-combo-btn').show().find('span').text('Print Selected Combo Labels (' + selectedStockIds.size + ')');
                } else {
                    $('#bulk-qr-label-btn').hide();
                    $('#bulk-combo-btn').hide();
                }
            }
        });

        // Print selected QR labels function (1.4" x 1.4" labels)
        function printSelectedQrLabels() {
            if (selectedStockIds.size === 0) { alert('Please select at least one item.'); return; }
            let form = $('<form>', { method: 'POST', action: '{{ route("stock.print.multiple.qrcode.labels") }}', target: '_blank' });
            form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
            selectedStockIds.forEach(function(id) {
                form.append($('<input>', { type: 'hidden', name: 'stock_ids[]', value: id }));
            });
            $('body').append(form); form.submit(); form.remove();
        }

        // Print selected combo labels function
        function printSelectedComboLabels() {
            if (selectedStockIds.size === 0) { alert('Please select at least one item.'); return; }
            let form = $('<form>', { method: 'POST', action: '{{ route("stock.print.multiple.combo") }}', target: '_blank' });
            form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
            selectedStockIds.forEach(function(id) {
                form.append($('<input>', { type: 'hidden', name: 'stock_ids[]', value: id }));
            });
            $('body').append(form); form.submit(); form.remove();
        }
    </script>
@endpush
