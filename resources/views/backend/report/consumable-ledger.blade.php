@extends('layouts.backend.app')

@section('title', 'Reports | Consumable Ledger')

@push('css')
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td {
            vertical-align: middle !important;
        }

        .filter-card .form-group {
            margin-bottom: 10px !important;
        }

        .kpi-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 6px;
        }

        .kpi-issue {
            background: #e3f2fd;
            color: #0d47a1;
        }

        .kpi-return {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .kpi-outstanding {
            background: #fff8e1;
            color: #e65100;
        }

        .net-negative {
            color: #2e7d32;
            font-weight: 600;
        }

        .net-positive {
            color: #1565c0;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>Consumable Ledger Report</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card filter-card">
                    <div class="body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Employee</label>
                                    <select id="employee_id" class="form-control select2-filter">
                                        <option value="">All Employees</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->emply_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Product Type</label>
                                    <select id="product_type" class="form-control select2-filter">
                                        <option value="">All Types</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select id="product_id" class="form-control select2-filter">
                                        <option value="">All Consumables</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Movement</label>
                                    <select id="movement_type" class="form-control select2-filter">
                                        <option value="">All</option>
                                        <option value="ISSUE">Issue</option>
                                        <option value="RETURN">Return</option>
                                        <option value="ADJUSTMENT">Adjustment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" id="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" id="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 8px;">
                            <div class="col-md-12">
                                <button type="button" id="clearFilters" class="btn btn-default btn-sm waves-effect">
                                    <i class="material-icons" style="font-size: 16px; vertical-align: middle;">clear_all</i>
                                    Clear Filters
                                </button>
                                <span class="kpi-pill kpi-issue">Issue Qty</span>
                                <span class="kpi-pill kpi-return">Return Qty</span>
                                <span class="kpi-pill kpi-outstanding">Outstanding Balance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Consumable Movements</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="consumableLedgerTable" width="100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Movement</th>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Product Type</th>
                                        <th>Product</th>
                                        <th>Issued</th>
                                        <th>Returned</th>
                                        <th>Net</th>
                                        <th>Outstanding</th>
                                        <th>Remarks</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
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
        $(function() {
            $('.select2-filter').select2({
                width: '100%'
            });

            const table = $('#consumableLedgerTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('reports.consumable.ledger.search') }}',
                    data: function(d) {
                        d.employee_id = $('#employee_id').val();
                        d.product_type = $('#product_type').val();
                        d.product_id = $('#product_id').val();
                        d.movement_type = $('#movement_type').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    { data: 'movement_date', name: 'cm.movement_date' },
                    { data: 'movement_type', name: 'cm.movement_type', searchable: false, orderable: false },
                    {
                        data: null,
                        name: 'employees.name',
                        render: function(data, type, row) {
                            if (!row.employee_name) {
                                return 'N/A';
                            }
                            return row.employee_name + (row.employee_code ? ' (' + row.employee_code + ')' : '');
                        }
                    },
                    { data: 'department_name', name: 'departments.name', defaultContent: 'N/A' },
                    { data: 'product_type_name', name: 'producttypes.name' },
                    { data: 'product_name', name: 'products.title' },
                    { data: 'issue_qty', name: 'issue_qty', className: 'text-right' },
                    { data: 'return_qty', name: 'return_qty', className: 'text-right' },
                    {
                        data: 'net_qty',
                        name: 'net_qty',
                        className: 'text-right',
                        render: function(data) {
                            const value = parseInt(data || 0, 10);
                            if (value < 0) {
                                return '<span class="net-negative">' + value + '</span>';
                            }
                            return '<span class="net-positive">' + value + '</span>';
                        }
                    },
                    { data: 'outstanding_qty', name: 'outstanding_qty', className: 'text-right' },
                    { data: 'remarks', name: 'cm.remarks', defaultContent: '' },
                    { data: 'created_by_name', name: 'users.name', defaultContent: 'System' }
                ],
                dom: 'Blfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                order: [[0, 'desc']],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
            });

            $('#employee_id, #product_type, #product_id, #movement_type, #start_date, #end_date').on('change', function() {
                table.ajax.reload();
            });

            $('#clearFilters').on('click', function() {
                $('#employee_id').val('').trigger('change');
                $('#product_type').val('').trigger('change');
                $('#product_id').val('').trigger('change');
                $('#movement_type').val('').trigger('change');
                $('#start_date').val('');
                $('#end_date').val('');
                table.ajax.reload();
            });
        });
    </script>
@endpush
