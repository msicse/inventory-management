@extends('layouts.backend.app')

@section('title','Reports | Employees')

@push('css')
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .table td{
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
    <div class="block-header">
        <h2>Reports</h2>

    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <form action="{{ route('reports.index') }}" method="get">


                        <div class="col-md-3">
                            <div class="form-group form-float">

                                <select name="department_id"  class="form-control show-tick" data-live-search="true" required>
                                    <option value="">Select Department</option>
                                    @foreach( $departments as $data)
                                    <option value="{{ $data->id }}"}}>{{ $data->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">

                                <select name="order_by"  class="form-control show-tick" required>
                                    <option value="">Select Order</option>
                                    <option value="asc">Ascending </option>
                                    <option value="desc">Descending </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" value="Submit" class="btn btn-success">
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
                        Transection Report

                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Office Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Products </th>
                                    <th>Image</th>
                                    <th>Action</th>

                                </tr>

                            </thead>
                            <tfoot>
                                <th>SL</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Office Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Products </th>
                                <th>Image</th>
                                <th>Action</th>
                            </tfoot>
                            <tbody>
                                @foreach( $employees as $key => $data )
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->emply_id }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->designation }}</td>
                                    <td>{{ $data->department->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->phone }}</td>
                                    <td>{!! $data->status == 1 ? "<span class=text-success>Active</span>" : "<span class=text-danger>Inactive</span>" !!} </td>
                                    <td>{{ $data->transections->count() }}</td>
                                    <td>
                                        <img src="{{ asset('images/employee/'.$data->image) }}"  alt=""  class="img-responsive" style="height: 100px;">
                                    </td>
                                    <td>
                                        <a href="{{ route('reports.show', $data->id) }}" class="btn btn-info waves-effect " >
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




    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>

<script>

$( ".delete" ).click(function() {
    var data_id=$(this).data('delete-id');
    var url=location.origin+'/admin/employees/status/'+data_id;
    $('.delete_form').attr('action',url);

});

</script>


@endpush

