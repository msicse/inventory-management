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

                    <!-- Summary Statistics Panel -->
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <div class="alert alert-info" style="margin-bottom: 10px;">
                                <strong><i class="material-icons" style="vertical-align: middle;">assessment</i> Report Summary:</strong>
                                <span id="totalItems">0</span> total items |
                                <span id="assignedItems">0</span> assigned |
                                <span id="availableItems">0</span> in storage |
                                <span id="goodCondition">0</span> good |
                                <span id="damagedItems">0</span> damaged/obsolete
                            </div>
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

            // Update summary statistics after table draws
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
                        length: -1 // Get all records for statistics
                    },
                    success: function(response) {
                        if (response.data) {
                            var total = response.data.length;
                            var assigned = 0;
                            var available = 0;
                            var good = 0;
                            var damaged = 0;

                            response.data.forEach(function(item) {
                                // Count assigned vs available
                                if (item.is_assigned == 1) {
                                    assigned++;
                                } else {
                                    available++;
                                }

                                // Count by condition
                                if (item.asset_condition && item.asset_condition.toLowerCase() === 'good') {
                                    good++;
                                } else if (item.asset_condition &&
                                          (item.asset_condition.toLowerCase() === 'damaged' ||
                                           item.asset_condition.toLowerCase() === 'obsolete')) {
                                    damaged++;
                                }
                            });

                            // Update the display
                            $('#totalItems').text(total);
                            $('#assignedItems').text(assigned);
                            $('#availableItems').text(available);
                            $('#goodCondition').text(good);
                            $('#damagedItems').text(damaged);
                        }
                    }
                });
            });

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
