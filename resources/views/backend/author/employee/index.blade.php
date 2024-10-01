@extends('layouts.backend.app')

@section('title','Author | Employees')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .table td{
            vertical-align: middle !important;
        }
        .mb {
            margin-bottom: 5px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('author.employees.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Employee</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Employees
                        <span class="badge ">{{ $employees->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Dept.</th>
                                    <th>Emp. ID</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Dept.</th>
                                    <th>Emp. ID</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $employees as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->department->name }}</td>
                                    <td>{{ $data->emply_id }}</td>
                                    <td>{{ $data->phone }} </td>
                                    <td>{{ $data->email }} </td>
                                    <td>{!! $data->status == 1 ? "<span class=text-success>Active</span>" : "<span class=text-danger>Inactive</span>" !!} </td>
                                    <td class="text-center"> <img src="{{ asset('images/employee/'. $data->image) }}" style="height:100px; width: 100px;" alt=""> </td>
                                    <td>
                                        <a href="{{ route('author.employees.show', $data->id) }}" title="View Employee Details" class="btn btn-info waves-effect mb" >
                                            <i class="material-icons">visibility</i>
                                        </a>

                                        <a href="{{ route('author.employees.edit', $data->id) }}" title="Edit Employee" class="btn btn-warning waves-effect edit mb">
                                            <i class="material-icons">create</i>
                                        </a>
                                        @if($data->status == 1)
                                        <button type="button" class="btn btn-danger waves-effect delete mb" title="Disable Employee" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal" >
                                            <i class="material-icons">person_off</i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-success waves-effect delete mb" title="Enable Employee" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal" >
                                            <i class="material-icons">person</i>
                                        </button>
                                        @endif


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


{{-- Delete Modal --}}
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog">
        <form class="delete_form" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Employee Status </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Are you sure to update the employee status ?</strong>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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
    var url=location.origin+'/author/employees/status/'+data_id;
    $('.delete_form').attr('action',url);

});

</script>


@endpush
