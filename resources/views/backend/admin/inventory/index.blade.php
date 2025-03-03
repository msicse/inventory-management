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
                                <select name="store" id="store" class="form-control show-tick" data-live-search="true">
                                    <option value="">All Location</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
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
                        Inventories
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="stockTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Type</th>
                                    <th>Product</th>
                                    <th>SN / IMEI </th>
                                    <th>Asset Tag</th>
                                    <th>Condition</th>
                                    <th title="Quantity">Qty</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Location</th>
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
                    url: '{{ route('inventories.index') }}',
                    data: function (d) {
                        // Pass custom filter data to the server
                        d.product_type = $('#type').val();
                        d.condition = $('#condition').val();
                        d.product_id = $('#store').val();
                        d.store = $('#store').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'product_type', name: 'producttypes.name', searchable: false },
                    { data: 'title', name: 'products.title' },
                    { data: 'service_tag', name: 'service_tag' },
                    { data: 'asset_tag', name: 'asset_tag' },
                    { data: 'asset_condition', name: 'asset_condition' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'supplier_company', name: 'suppliers.company' },
                    { data: 'purchase_date', name: 'stocks.purchase_date',  searchable: false },
                    { data: 'assigned_to', name: 'assigned_to', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ],
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            modifier: { page: 'all' } // Exports all pages
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'pdf',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' }
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
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

            // Custom Filters Trigger Table Reload
            $('#type, #condition, #store, #supplier').on('change', function () {
                table.ajax.reload();
            });

            $('#product_id').select2({
                width: '100%',
            });

            // Initialize Select2
            $('#type').select2();
            $('#store').select2();
            $('#supplier').select2();
            $('#condition').select2();

        });
    </script>
@endpush
