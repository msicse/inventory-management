@extends('layouts.backend.app')

@section('title', 'Reports | Stocks')

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
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="card">
                    <div class="header">
                        <h2>
                            Stock Report
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>Admin Return</th>
                                        <th>Stolen</th>
                                        <th>Assigned</th>
                                        <th>Support</th>
                                        <th>Current Stock</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($stocks as $key => $data)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->stocks->where("store_id", 3)->count() }}</td>
                                            <td>{{ $data->stocks->where("condition", 'stolen')->count() }}</td>
                                            <td>{{ $data->stocks->where("is_assigned", 1)->count() }}</td>
                                            <td>{{ $data->stocks->where("is_assigned", 1)->count() }}</td>
                                            <td>{{ $data->stocks->where("is_assigned", 2)->whereIn('store_id',[1,2])->count() }}</td>

                                            <td>{{ $data->stocks->count() }}</td>
                                            <td>

                                                <a href="{{ route('reports.stocks.details', $data->id) }}"
                                                    class="btn btn-info waves-effect ">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
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


    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>




    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <script>
          $(document).ready(function(){

            $('#product_id').select2({
                width: '100%',
            });

        });



        $(".delete").click(function() {
            var data_id = $(this).data('delete-id');
            var url = location.origin + '/admin/employees/status/' + data_id;
            $('.delete_form').attr('action', url);

        });
    </script>
@endpush
