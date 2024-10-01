@extends('layouts.backend.app')

@section('title','Admin | Purchases')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <style>
        .table td{
            vertical-align: middle !important;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('purchases.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Purchases</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Purchases
                        <span class="badge ">{{ $purchases->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Contact person</th>
                                    <th>Invoice No.</th>
                                    <th>Chalan No.</th>
                                    <th>Total Price</th>
                                    <th>Date of Purchase</th>
                                    <th>In Inventory</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Contact Person</th>
                                    <th>Invoice No.</th>
                                    <th>Chalan No.</th>
                                    <th>Total Price</th>
                                    <th>Date of Purchase</th>
                                    <th>In Inventory</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $purchases as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->supplier->company }}</td>
                                    <td>{{ $data->supplier->name }}</td>
                                    <td>{{ $data->invoice_no }} </td>
                                    <td>{{ $data->challan_no }} </td>
                                    <td>{{ $data->total_price }} </td>
                                    <td>{{ $data->purchase_date }} </td>
                                    <th>{{ $data->is_stocked == 2 ? "No" : "Yes" }}</th>
                                    
                                    <td>
                                        <a href="{{ route('purchases.show', $data->id ) }}" class="btn btn-info waves-effect " >
                                            <i class="material-icons">visibility</i>
                                        </a>

                                        <a href="{{ route('purchases.grn', $data->id) }}" target="blank" title="Print GRN" class="btn btn-primary waves-effect "  style="margin-top: 5px;">
                                            <i class="material-icons">print</i>
                                        </a>
                                        <a href="{{ route('purchases.edit', $data->id) }}" class="btn btn-warning waves-effect edit">
                                            <i class="material-icons">create</i>
                                        </a>
                                        @if($data->status == 1)
                                        <button type="button" class="btn btn-danger waves-effect delete" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal" >
                                            <i class="material-icons">person_off</i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-danger waves-effect delete" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal" >
                                            <i class="material-icons">delete</i>
                                        </button>
                                        @endif

                                        @if($data->is_stocked == 2)
                                        <button class="btn btn-success waves-effect" title="Add to Inventory" onclick="if(confirm('Are You sure to Add the Products to Inventory?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{ $data->id }}').submit();
                                            } else {
                                            event.preventDefault();
                                            }">
    
                                                <i class="material-icons">add</i>
                                            </button>
    
                                            <form id="delete-form-{{ $data->id }}" style="display: none;"
                                                action="{{  route('purchases.inventory',$data->id) }}" method="post">
                                                @csrf
                                                
                                        
                                            </form>
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
    var url=location.origin+'/admin/employees/status/'+data_id;
    $('.delete_form').attr('action',url);

});

</script>


@endpush
