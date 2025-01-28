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
                            <div class="form-group">
                                <input type="date" id="startDateFilter" class="form-control" placeholder="Start Date">
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" id="endDateFilter" class="form-control" placeholder="End Date">
                            </div>

                        </div>

                    </div>
                    {{-- <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-cyan hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">laptop</i>
                                </div>
                                <div class="content">
                                    <div class="text">Assigned Laptop</div>
                                    <div class="number count-to" data-from="0" data-to="111" data-speed="1000"
                                        data-fresh-interval="20"></div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

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
                                    <th>Serial </th>
                                    <th>Purchase Date</th>
                                    <th title="days remain">Warranty(days)</th>
                                    <th>Supplier</th>
                                    <th>Condition</th>
                                    <th>Location</th>
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
                        d.start_date = $('#startDateFilter').val();
                        d.end_date = $('#endDateFilter').val();

                    }
                },
                columns: [
                    { data: 'product_type', name: 'producttypes.name' },
                    { data: 'product_brand', name: 'products.brand' },
                    { data: 'product_model', name: 'products.model' },
                    { data: 'asset_tag', name: 'asset_tag' },
                    { data: 'service_tag', name: 'service_tag' },
                    { data: 'purchase_date', name: 'purchases.purchase_date' },
                    { data: 'warranty_remaining', name: 'warranty_remaining', searchable: false },
                    { data: 'supplier_company', name: 'suppliers.company' },
                    { data: 'condition', name: 'condition' },
                    { data: 'assigned_to', name: 'assigned_to', searchable: false },
                    { data: 'purchase_invoice', name: 'purchases.invoice_no' },


                ],
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });

            // Custom Filters Trigger Table Reload
            $('#type, #model, #condition, #store, #supplier, #startDateFilter, #endDateFilter').on('change', function () {
                table.ajax.reload();
            });

            $('#product_id').select2({
                width: '100%',
            });

            // Initialize Select2
            $('#type').select2();
            $('#status').select2();
            $('#store').select2();
            $('#supplier').select2();
            $('#condition').select2();
            $('#model').select2();

            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            });

        });
    </script>
@endpush
