@extends('layouts.backend.app')

@section('title', 'Admin | Purchases')

@push('css')
<!-- JQuery DataTable Css -->
<link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
<style>
    .table td {
        vertical-align: middle !important;
    }

</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('purchases.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;">
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
                        All Purchased Products
                        <span class="badge ">{{ $products->count() }}</span>

                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Serial No</th>
                                    <th>Warranty</th>
                                    <th>Expired Date</th>
                                    <th>Is Stocked</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Serial No</th>
                                    <th>Warranty</th>
                                    <th>Expired Date</th>
                                    <th>Is Stocked</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($products as $key => $data)

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->product->title }}</td>
                                    <td>{{ $data->product->type->name }}</td>
                                    <td>{{ $data->unit_price }}</td>
                                    <td>{{ $data->quantity }}</td>
                                    <td>{{ $data->total_price }}</td>
                                    <td>{{ $data->serials }}</td>
                                    <td>{{ $data->warranty }}</td>
                                    <td>{{ $data->expired_date }}</td>
                                    <td>{{ $data->is_stocked == 1 ? "Yes" : "No" }}</td>


                                    <td>
                                        <a href="{{ route('purchased.products.show', $data->id) }}" class="btn btn-info waves-effect ">
                                            <i class="material-icons">visibility</i>
                                        </a>

                                        @if ($data->is_stocked == 2)
                                        <button class="btn btn-success waves-effect" title="Add to Inventory" onclick="if(confirm('Are You sure to Add the Products to Inventory?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{ $data->id }}').submit();
                                            } else {
                                            event.preventDefault();
                                            }">

                                            <i class="material-icons">add</i>
                                        </button>

                                        <form id="delete-form-{{ $data->id }}" style="display: none;" action="{{ route('purchases.inventory', $data->id) }}" method="post">
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
    $(".delete").click(function() {
        var data_id = $(this).data('delete-id');
        var url = location.origin + '/admin/employees/status/' + data_id;
        $('.delete_form').attr('action', url);

    });

</script>
@endpush
