@extends('layouts.backend.app')

@section('title', 'Reports | Stock| Details')

@push('css')
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
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

                        <form action="{{ route('reports.transections') }}" method="get">
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <select name="" class="form-control show-tick" data-live-search="true"
                                            required>
                                            <option value="">Type</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">

                                        <select name="product_id" class="form-control show-tick" data-live-search="true"
                                            required>
                                            <option value="">Model </option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">

                                        <select name="product_id" class="form-control show-tick" data-live-search="true">
                                            <option value=""> Supplier</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">

                                        <select name="employee_id" class="form-control show-tick" data-live-search="true"
                                            required>
                                            <option value="">Status</option>
                                            <option value="1">Active</option>
                                            <option value="3">Damaged</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                            <input type="text" name="from" value="{{ old('form') }}"
                                                class="datepicker form-control" placeholder="From"
                                                >
                                    </div>

                                </div>
                                <div class="col-md-2">
                                <div class="form-group">
                                            <input type="text" name="to" value="{{ old('to') }}"
                                                class="datepicker form-control" placeholder="To"
                                                >
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 text-center">
                                    <input type="submit" value="Submit" class="btn btn-success">
                                </div>
                            </div>


                        </form>
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
                            Stock Reports
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Type</th>
                                        <th>Product</th>
                                        <th>Model</th>
                                        <th>Asset Tag</th>
                                        <th>Serial </th>
                                        <th>Purchase Date</th>
                                        <th>Warranty</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                        <th>Invoice</th>
                                        <th>Assigned</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($stocks as $key => $data)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->product->type->name }}</td>
                                            <td>{{ $data->product->title }}</td>
                                            <td>{{ $data->product->model }}</td>
                                            <td>{{ $data->asset_tag }}</td>
                                            <td>{{ $data->service_tag }}</td>
                                            <td>{{ $data->purchase_date }} </td>
                                            <td>{{ getDateDiff($data->expired_date) }} days</td>
                                            <td>{{ $data->purchase->supplier->company }}</td>
                                            <td> <span class="{{ productStatus($data->product_status) == 'Damaged' ? 'text-danger' : 'text-success'}}">{{ productStatus($data->product_status)}}</span></td>
                                            <td>{{ $data->purchase->invoice_no }}</td>
                                            <td><span class="{{ $data->is_assigned == 1 ? 'text-success' : 'text-danger' }}">{{ $data->is_assigned == 1 ? "Yes" : "No" }}</span></td>
                                        </tr>
                                    @endforeach

                                </tbody>
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
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>


    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>

    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <script>
          $(document).ready(function(){

            $('#product_id').select2({
                width: '100%',
            });

        });

        $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            });
    </script>
@endpush
