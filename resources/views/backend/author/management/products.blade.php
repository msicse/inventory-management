@extends('layouts.backend.app')

@section('title','Author | Management | Employees')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .table td{
            vertical-align: middle !important;
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
                        Manage Products
                        <span class="badge ">{{ $products->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Serial</th>
                                    <th>Services Tag</th>
                                    <th>MAC</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Serial</th>
                                    <th>Services Tag</th>
                                    <th>MAC</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $products as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->product->title }}</td>
                                    <td>{{ $data->product->type }}</td>
                                    <td>{{ $data->serial_no }}</td>
                                    <td>{{ $data->service_tag }}</td>
                                    <td>{{ $data->mac }}</td>
                                   
                                    <td>
                                        @if($data->product_status == 1)
                                        <span class=text-success>Active</span>
                                        @elseif($data->product_status == 2)
                                        <span class=text-warning>Poor</span>
                                        @else
                                        <span class=text-danger>Damage</span>
                                        @endif
                                        
                                    </td>

                                    <td>
                                        <form action="{{ route('author.management.products.update', $data->id ) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success waves-effect " name="submit" value="active">Active</button>
                                            <button type="submit" class="btn btn-warning waves-effect " name="submit" value="poor">Poor</button>
                                            <button type="submit" class="btn btn-danger waves-effect " name="submit" value="damage">Damage</button>
                                        </form>
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
