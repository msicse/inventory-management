@extends('layouts.backend.app')

@section('title','Author | Inventories')

@push('css')
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
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        {{-- <a href="{{ route('author.inventories.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Transection</span>
        </a> --}}

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Inventories
                        <span class="badge ">{{ $inventories->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Serial No</th>
                                    <th>Service Tag</th>
                                    <th>MAC</th>
                                    <th>Product Status</th>
                                    {{-- <th>Date of Purchase</th> --}}
                                    <th>Is Assigned</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Serial No</th>
                                    <th>Service Tag</th>
                                    <th>MAC</th>
                                    <th>Product Status</th>
                                    
                                    {{-- <th>Date of Purchase</th> --}}
                                    <th>Is Assigned</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $inventories as $key => $data)

                                
                                
                                <tr>
                                    <form action="{{ route('author.inventories.update', $data->id) }}" method="post" >
                                        @csrf
                                        @method('PUT')
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->product->title }}</td>
                                    <td>{{ empty($data->serial_no) ? "" : "RSC-".$data->serial_no }} </td>
                                    <td>
                                        @empty($data->service_tag)
                                        <input type="text" class="custom_width" name="service_tag" required>
                                        @else
                                        {{ $data->service_tag }}
                                        @endempty
                                    </td>
                                    
                                    <td>
                                    @empty($data->mac)
                                        <input type="text" name="mac" class="custom_width2" value="{{ $data->mac }}" > 
                                    @else
                                    {{ $data->mac }}
                                    @endempty
                                    </td>
                                    <td>
                                        @if($data->product_status == 1)
                                        <span class="text-success"> Active </span>
                                        @elseif($data->product_status == 2)
                                        <span class="text-warning"> Poor </span>
                                        @else
                                        <span class="text-danger"> Damage </span>
                                        @endif
                                    </td>
                                    {{-- <td>{{ $data->purchase->supplier->company }}</td>
                                    <td>{{ $data->purchase->purchase_date }} </td> --}}
                                    <td>
                                        @if( $data->is_assigned == 1)
                                        <span class="text-success">Yes</span>
                                        @else 
                                        <span class="text-danger">No</span>
                                        @endif
                                    </td>
                                
                                    <td>
                                        <a href=" {{ route('author.inventories.show', $data->id) }}" class="btn btn-info waves-effect " >
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        @if( empty($data->service_tag) OR empty($data->mac) )
                                        <button type="submit"  title="Update Inventory " class="btn btn-success waves-effect">
                                            <i class="material-icons">update</i>
                                        </button>
                                        @endif

                                    </td>
                                </form>
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
