@extends('layouts.backend.app')

@section('title','Admin | Inventories')

@push('css')
<!-- JQuery DataTable Css -->
<link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">

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

</style>
@endpush
@section('content')
<div class="container-fluid">
    {{-- <div class="block-header">
        <a href="{{ route('inventories.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
    <i class="material-icons">add</i>
    <span>Add New Purchases</span>
    </a>

</div> --}}
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
                                <th>SN / IMEI</th>
                                {{-- <th>Serial No</th> --}}
                                <th>Asset Tag</th>
                                <th>Product Status</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Expaired Date</th>
                                <th>Is Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>SN / IMEI</th>
                                {{-- <th>Serial No</th> --}}
                                <th>Asset Tag</th>
                                <th>Product Status</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Expaired Date</th>
                                <th>Is Assigned</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach( $inventories as $key => $data)

                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->product->title }}</td>
                                <td>{{ $data->service_tag }} </td>
                                <td>
                                    @empty($data->asset_tag)
                                    <input type="text" class="custom_width" name="asset_tag" value="{{ $data->asset_tag }}" id="service-{{ $data->id }}" required>
                                    @else
                                    {{ $data->asset_tag }}
                                    @endempty

                                </td>
                                <td>
                                    @if($data->product_status == 1)
                                    <span class="text-success"> Active </span>
                                    <!-- elseif($data->product_status == 2) -->
                                    <!-- <span class="text-warning"> Poor </span> -->
                                    @else
                                    <span class="text-danger"> Damage </span>
                                    @endif
                                </td>
                                <td>
                                    @if($data->producttype->slug == 'software')
                                    {{ $data->quantity }},
                                    Assigned - {{ $data->assigned }} 
                                    @endif

                                </td>
                                <td>{{ $data->purchase->supplier->company }}</td>
                                <td>{{ $data->purchase->purchase_date }} </td> 
                                <td>{{ $data->purchase->expired_date }} </td> 
                                <td>
                                    @if( $data->is_assigned == 1)
                                    <span class="text-success">Yes</span>
                                    @else
                                    <span class="text-danger">No</span>
                                    @endif
                                </td>

                                <td>
                                    <a href=" {{ route('inventories.show', $data->id) }}" class="btn btn-info waves-effect ">
                                        <i class="material-icons">visibility</i>
                                    </a>

                                    @empty($data->asset_tag)
                                    <button type="button" title="Update Inventory " data-inv-id="{{ $data->id }}" class="btn btn-success waves-effect updateInv">
                                        <i class="material-icons">update</i>
                                    </button>
                                    @endempty

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
    $(".updateInv").click(function() {
        // toastr.success('Click Button');
        var invId = $(this).data('inv-id');
        var asset = $("#service-" + invId).val();
        var url = location.origin + '/inventories/' + invId;

        $.ajax({
            url: url
            , type: "POST"
            , data: {
                "_token": "{{ csrf_token() }}"
                , asset_tag: asset,

            }
            , success: function(response) {

                if (response['status'] == 200) {
                    toastr.success(response['message']);
                } else {
                    toastr.error(response['message']);
                }
            }

        });

    });

</script>

@endpush
