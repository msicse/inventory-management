@extends('layouts.backend.app')

@section('title', 'Reports | Stock | Details')

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

        .hover-zoom-effect:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .info-box-3 {
            transition: all 0.3s ease;
        }

        .info-box-3 .content {
            padding-right: 10px;
            overflow: hidden;
        }

        .info-box-3 .content .number {
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .info-box-3 .content .text {
            font-size: 11px;
            margin-bottom: 5px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select id="type" class="form-control show-tick" data-live-search="true">
                                    <option value="">All Type</option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}">{{ $type->name }}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">

                                <select name="model" id="model" class="form-control show-tick" data-live-search="true">
                                    <option value="">All Model </option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->model }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="condition" id="condition" class="form-control form-control-sm show-tick"
                                    data-live-search="true">
                                    <option value="">All Condition</option>
                                    <option value="good">Good</option>
                                    <option value="obsolete ">Obsolete </option>
                                    <option value="damaged">Damaged</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="store" id="store" class="form-control show-tick" data-live-search="true">
                                    <option value="">All Location</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">

                                <select name="supplier" id="supplier" class="form-control show-tick"
                                    data-live-search="true">
                                    <option value="">All Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->company }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="department" id="department" class="form-control show-tick"
                                    data-live-search="true">
                                    <option value="">All Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" id="startDateFilter" class="form-control" placeholder="Start Date">
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" id="endDateFilter" class="form-control" placeholder="End Date">
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="asset_status" id="asset_status" class="form-control show-tick"
                                    data-live-search="true">
                                    <option value="">All Asset Status</option>
                                    <option value="active">Active</option>
                                    <option value="retired">Retired</option>
                                    <option value="under repair">Under Repair</option>
                                    <option value="disposed">Disposed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="assignment_status" id="assignment_status" class="form-control show-tick">
                                    <option value="">All Items</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="available">Available</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select name="warranty_status" id="warranty_status" class="form-control show-tick">
                                    <option value="">All Warranty</option>
                                    <option value="active">Active</option>
                                    <option value="expiring">Expiring Soon (≤30 days)</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Modern Dashboard Summary Panel -->
                    <div class="row" style="margin-top: 20px;">
                        <!-- Statistics Cards -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="info-box-3 bg-cyan hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons">inventory_2</i>
                                </div>
                                <div class="content">
                                    <div class="text">TOTAL ITEMS</div>
                                    <div class="number" id="totalItems" style="font-size: 28px; font-weight: bold;">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="info-box-3 bg-green hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="content">
                                    <div class="text">ASSIGNED</div>
                                    <div class="number" id="assignedItems" style="font-size: 28px; font-weight: bold;">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="info-box-3 bg-orange hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons">store</i>
                                </div>
                                <div class="content">
                                    <div class="text">IN STORAGE</div>
                                    <div class="number" id="availableItems" style="font-size: 28px; font-weight: bold;">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="info-box-3 bg-light-blue hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons">attach_money</i>
                                </div>
                                <div class="content" style="padding-right: 15px;">
                                    <div class="text">TOTAL VALUE</div>
                                    <div class="number" id="totalValue" style="font-size: 18px; font-weight: bold; word-break: break-word; line-height: 1.1;">$0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="header bg-cyan">
                                    <h2 style="color: white; font-size: 16px;">
                                        <i class="material-icons" style="vertical-align: middle;">pie_chart</i>
                                        CONDITION STATUS
                                    </h2>
                                </div>
                                <div class="body" style="height: 250px;">
                                    <canvas id="conditionChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="header bg-green">
                                    <h2 style="color: white; font-size: 16px;">
                                        <i class="material-icons" style="vertical-align: middle;">donut_small</i>
                                        ASSIGNMENT STATUS
                                    </h2>
                                </div>
                                <div class="body" style="height: 250px;">
                                    <canvas id="assignmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="header bg-orange">
                                    <h2 style="color: white; font-size: 16px;">
                                        <i class="material-icons" style="vertical-align: middle;">warning</i>
                                        WARRANTY STATUS
                                    </h2>
                                </div>
                                <div class="body" style="height: 250px;">
                                    <canvas id="warrantyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Analytics Row -->
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header bg-indigo">
                                    <h2 style="color: white; font-size: 16px;">
                                        <i class="material-icons" style="vertical-align: middle;">bar_chart</i>
                                        TOP 10 DEPARTMENTS BY ASSETS
                                    </h2>
                                </div>
                                <div class="body" style="height: 300px;">
                                    <canvas id="departmentChart"></canvas>
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
                        Detailed Inventory Report
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="stockTable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Asset Tag</th>
                                    <th>Serial</th>
                                    <th title="Purchase Date">Purchase Date</th>
                                    <th title="Purchase Price">Price</th>
                                    <th title="Warranty Remaining(days)">Warr. Days</th>
                                    <th title="Warranty Expiry Date">Warr. Expiry</th>
                                    <th>Supplier</th>
                                    <th>Condition</th>
                                    <th title="Asset Status">Status</th>
                                    <th>User/Location</th>
                                    <th>Department</th>
                                    <th title="Assignment Date">Assigned</th>
                                    <th>Invoice</th>
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
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

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

    <!-- Moment Plugin Js -->
    <script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>
    {{--
    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script> --}}
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {

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
                    url: '{{ route('reports.inventory.search') }}',
                    data: function (d) {
                        // Pass custom filter data to the server
                        d.product_type = $('#type').val();
                        d.product_model = $('#model').val();
                        d.condition = $('#condition').val();
                        d.product_id = $('#store').val();
                        d.supplier = $('#supplier').val();
                        d.store = $('#store').val();
                        d.department = $('#department').val();
                        d.start_date = $('#startDateFilter').val();
                        d.end_date = $('#endDateFilter').val();
                        d.asset_status = $('#asset_status').val();
                        d.assignment_status = $('#assignment_status').val();
                        d.warranty_status = $('#warranty_status').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', error);
                        if (xhr.status === 422) {
                            alert('Validation Error: ' + JSON.stringify(xhr.responseJSON.messages));
                        } else if (xhr.status === 500) {
                            alert('Server Error: Unable to load inventory data. Please try again.');
                        } else {
                            alert('Error loading data: ' + thrown);
                        }
                    }
                },
                columns: [
                    { data: 'product_type', name: 'producttypes.name' },
                    { data: 'product_brand', name: 'products.brand' },
                    { data: 'product_model', name: 'products.model' },
                    { data: 'asset_tag', name: 'asset_tag' },
                    { data: 'service_tag', name: 'service_tag' },
                    {
                        data: 'purchase_date',
                        name: 'purchases.purchase_date',
                        title: 'Purchase Date',
                        render: function(data, type, row) {
                            if (data) {
                                let date = new Date(data);
                                return '<span style="white-space: nowrap;">' + date.toLocaleDateString('en-GB') + '</span>';
                            }
                            return '<span style="color: #999;">-</span>';
                        }
                     },
                    {
                        data: 'purchase_price',
                        name: 'purchase_products.unit_price',
                        title: 'Purchase Price',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (data && data > 0) {
                                    return '<span style="font-weight: 600; color: #2c3e50;">$' + parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</span>';
                                }
                                return '<span style="color: #999;">N/A</span>';
                            }
                            return data;
                        }
                    },
                    { data: 'warranty_remaining', name: 'warranty_remaining', searchable: false,
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (data <= 0) {
                                    return '<span style="color: #e74c3c; font-weight: bold;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">error</i> Expired</span>';
                                } else if (data <= 30) {
                                    return '<span style="color: #e67e22; font-weight: bold;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">warning</i> ' + data + '</span>';
                                } else if (data <= 90) {
                                    return '<span style="color: #f39c12;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">schedule</i> ' + data + '</span>';
                                } else {
                                    return '<span style="color: #27ae60;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">check_circle</i> ' + data + '</span>';
                                }
                            }
                            return data;
                        }
                    },
                    {
                        data: 'expired_date',
                        name: 'stocks.expired_date',
                        title: 'Warranty Expiry',
                        render: function(data, type, row) {
                            if (data) {
                                let date = new Date(data);
                                let today = new Date();
                                let isExpired = date < today;
                                let color = isExpired ? '#e74c3c' : '#27ae60';
                                return '<span style="color: ' + color + '; white-space: nowrap;">' + date.toLocaleDateString('en-GB') + '</span>';
                            }
                            return '<span style="color: #999;">-</span>';
                        }
                    },
                    { data: 'supplier_company', name: 'suppliers.company' },
                    { data: 'asset_condition', name: 'asset_condition',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                var condition = data.toLowerCase();
                                if (condition === 'good') {
                                    return '<span class="label bg-green"><i class="material-icons" style="font-size: 12px; vertical-align: middle;">check_circle</i> ' + data + '</span>';
                                } else if (condition === 'damaged') {
                                    return '<span class="label bg-red"><i class="material-icons" style="font-size: 12px; vertical-align: middle;">cancel</i> ' + data + '</span>';
                                } else if (condition === 'obsolete') {
                                    return '<span class="label bg-orange"><i class="material-icons" style="font-size: 12px; vertical-align: middle;">block</i> ' + data + '</span>';
                                }
                            }
                            return data || '<span style="color: #999;">N/A</span>';
                        }
                    },
                    {
                        data: 'asset_status',
                        name: 'asset_statuses.name',
                        title: 'Status',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                var status = data.toLowerCase();
                                var badge = 'bg-blue';
                                var icon = 'info';

                                if (status.includes('active') || status.includes('in use')) {
                                    badge = 'bg-green';
                                    icon = 'check_circle';
                                } else if (status.includes('repair') || status.includes('maintenance')) {
                                    badge = 'bg-orange';
                                    icon = 'build';
                                } else if (status.includes('retired') || status.includes('disposed')) {
                                    badge = 'bg-red';
                                    icon = 'delete';
                                } else if (status.includes('spare') || status.includes('storage')) {
                                    badge = 'bg-cyan';
                                    icon = 'inventory';
                                }

                                return '<span class="label ' + badge + '"><i class="material-icons" style="font-size: 12px; vertical-align: middle;">' + icon + '</i> ' + data + '</span>';
                            }
                            return '<span style="color: #999;">N/A</span>';
                        }
                    },
                    { data: 'assigned_to', name: 'employees.name' },
                    { data: 'department_name', name: 'departments.name', defaultContent: '<span style="color: #999;">N/A</span>' },
                    {
                        data: 'assignment_date',
                        name: 'latest_trans.issued_date',
                        title: 'Assignment Date',
                        render: function(data, type, row) {
                            if (data && row.is_assigned == 1) {
                                let date = new Date(data);
                                let today = new Date();
                                let diffTime = Math.abs(today - date);
                                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                                let color = '#3498db';
                                if (diffDays > 365) color = '#e74c3c';
                                else if (diffDays > 180) color = '#e67e22';

                                return '<span style="color: ' + color + '; white-space: nowrap;" title="' + diffDays + ' days ago">' +
                                       '<i class="material-icons" style="font-size: 14px; vertical-align: middle;">event</i> ' +
                                       date.toLocaleDateString('en-GB') + '</span>';
                            }
                            return '<span style="color: #999;">Not Assigned</span>';
                        }
                    },
                    { data: 'purchase_invoice', name: 'purchases.invoice_no' },


                ],
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        exportOptions: {
                            modifier: { page: 'all' }
                        },
                        action: function (e, dt, button, config) {
                            var count = dt.rows({search: 'applied'}).count();
                            if (count > 10000) {
                                if (!confirm('You are about to export ' + count + ' rows. This may take a while. Continue?')) {
                                    return;
                                }
                            }
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            modifier: { page: 'all' }
                        },
                        action: function (e, dt, button, config) {
                            var count = dt.rows({search: 'applied'}).count();
                            if (count > 10000) {
                                if (!confirm('You are about to export ' + count + ' rows. This may take a while. Continue?')) {
                                    return;
                                }
                            }
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' }
                        },
                        action: function (e, dt, button, config) {
                            var count = dt.rows({search: 'applied'}).count();
                            if (count > 5000) {
                                if (!confirm('You are about to export ' + count + ' rows to PDF. This may take a while. Continue?')) {
                                    return;
                                }
                            }
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                        },
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 9;
                            doc.styles.tableHeader.fontSize = 10;
                            doc.styles.tableHeader.bold = true;
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';
                            doc.pageMargins = [10, 10, 10, 10];

                            // Add title
                            doc.content.splice(0, 0, {
                                text: 'Detailed Inventory Report',
                                style: 'header',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });

                            // Add generation date
                            doc.content.splice(1, 0, {
                                text: 'Generated on: ' + new Date().toLocaleDateString(),
                                alignment: 'center',
                                margin: [0, 0, 0, 10],
                                fontSize: 9
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            modifier: { page: 'all' }
                        },
                        customize: function (win) {
                            $(win.document.body).prepend(
                                '<h2 style="text-align:center;">Detailed Inventory Report</h2>' +
                                '<p style="text-align:center;">Generated on: ' + new Date().toLocaleDateString() + '</p>'
                            );
                            $(win.document.body).find('table').addClass('display').css('font-size', '10pt');
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });

            // Custom Filters Trigger Table Reload
            $('#type, #model, #condition, #store, #supplier, #department, #startDateFilter, #endDateFilter, #asset_status, #assignment_status, #warranty_status').on('change', function () {
                table.ajax.reload();
            });

// Update summary statistics and charts after table draws
            table.on('draw', function() {
                $.ajax({
                    url: '{{ route('reports.inventory.search') }}',
                    type: 'GET',
                    data: {
                        product_type: $('#type').val(),
                        product_model: $('#model').val(),
                        condition: $('#condition').val(),
                        supplier: $('#supplier').val(),
                        store: $('#store').val(),
                        department: $('#department').val(),
                        start_date: $('#startDateFilter').val(),
                        end_date: $('#endDateFilter').val(),
                        asset_status: $('#asset_status').val(),
                        assignment_status: $('#assignment_status').val(),
                        warranty_status: $('#warranty_status').val(),
                        length: -1 // Get all records for statistics
                    },
                    success: function(response) {
                        if (response.data) {
                            updateDashboard(response.data);
                        }
                    }
                });
            });

            // Dashboard update function with charts
            let conditionChart, assignmentChart, warrantyChart, departmentChart;

            function updateDashboard(data) {
                var total = data.length;
                var assigned = 0;
                var available = 0;
                var good = 0;
                var damaged = 0;
                var obsolete = 0;
                var totalValue = 0;
                var warrantyActive = 0;
                var warrantyExpiring = 0;
                var warrantyExpired = 0;
                var departmentCount = {};

                data.forEach(function(item) {
                    // Count assigned vs available
                    if (item.is_assigned == 1) {
                        assigned++;
                    } else {
                        available++;
                    }

                    // Count by condition
                    if (item.asset_condition) {
                        var cond = item.asset_condition.toLowerCase();
                        if (cond === 'good') good++;
                        else if (cond === 'damaged') damaged++;
                        else if (cond === 'obsolete') obsolete++;
                    }

                    // Calculate total value
                    if (item.purchase_price) {
                        totalValue += parseFloat(item.purchase_price);
                    }

                    // Warranty status
                    if (item.warranty_remaining) {
                        var days = parseInt(item.warranty_remaining);
                        if (days <= 0) warrantyExpired++;
                        else if (days <= 30) warrantyExpiring++;
                        else warrantyActive++;
                    }

                    // Department count
                    if (item.department_name && item.is_assigned == 1) {
                        var dept = item.department_name;
                        departmentCount[dept] = (departmentCount[dept] || 0) + 1;
                    }
                });

                // Update statistics cards
                $('#totalItems').text(total.toLocaleString());
                $('#assignedItems').text(assigned.toLocaleString());
                $('#availableItems').text(available.toLocaleString());
                $('#totalValue').text('$' + totalValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                // Condition Chart (Doughnut)
                var conditionCtx = document.getElementById('conditionChart').getContext('2d');
                if (conditionChart) conditionChart.destroy();
                conditionChart = new Chart(conditionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Good', 'Damaged', 'Obsolete'],
                        datasets: [{
                            data: [good, damaged, obsolete],
                            backgroundColor: ['#27ae60', '#e74c3c', '#e67e22'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 12 } }
                            }
                        }
                    }
                });

                // Assignment Chart (Pie)
                var assignmentCtx = document.getElementById('assignmentChart').getContext('2d');
                if (assignmentChart) assignmentChart.destroy();
                assignmentChart = new Chart(assignmentCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Assigned', 'Available'],
                        datasets: [{
                            data: [assigned, available],
                            backgroundColor: ['#00bcd4', '#ff9800'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 12 } }
                            }
                        }
                    }
                });

                // Warranty Chart (Doughnut)
                var warrantyCtx = document.getElementById('warrantyChart').getContext('2d');
                if (warrantyChart) warrantyChart.destroy();
                warrantyChart = new Chart(warrantyCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Expiring (≤30d)', 'Expired'],
                        datasets: [{
                            data: [warrantyActive, warrantyExpiring, warrantyExpired],
                            backgroundColor: ['#4caf50', '#ffc107', '#f44336'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11 } }
                            }
                        }
                    }
                });

                // Department Chart (Horizontal Bar) - Top 10
                var sortedDepts = Object.entries(departmentCount)
                    .sort((a, b) => b[1] - a[1])
                    .slice(0, 10);
                var deptLabels = sortedDepts.map(d => d[0]);
                var deptValues = sortedDepts.map(d => d[1]);

                var departmentCtx = document.getElementById('departmentChart').getContext('2d');
                if (departmentChart) departmentChart.destroy();
                departmentChart = new Chart(departmentCtx, {
                    type: 'bar',
                    data: {
                        labels: deptLabels,
                        datasets: [{
                            label: 'Assets Assigned',
                            data: deptValues,
                            backgroundColor: '#3f51b5',
                            borderColor: '#303f9f',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            $('#product_id').select2({
                width: '100%',
            });

            // Initialize Select2
            $('#type').select2();
            $('#status').select2();
            $('#store').select2();
            $('#supplier').select2();
            $('#department').select2();
            $('#condition').select2();
            $('#model').select2();
            $('#asset_status').select2();
            $('#assignment_status').select2();
            $('#warranty_status').select2();

            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            });

        });
    </script>
@endpush
