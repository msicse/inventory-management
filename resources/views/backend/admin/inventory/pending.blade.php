@extends('layouts.backend.app')

@section('title','Admin | Inventories | Pending Tag')

@push('css')
<!-- JQuery DataTable Css -->
<link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
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
                    Pending List for Updating Asset Tag
                    <span class="badge ">{{ $inventories->count() }}</span>
                </h2>
            </div>

            <div class="header">
                <form action="{{ route("inventories.pending") }}" method="GET" id="filerForm">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="type" id="type" class="form-control show-tick" data-live-search="true">
                                <option value="">Product Type</option>
                                @foreach( $types as $type )
                                <option value="{{$type->id}}">{{ $type->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="status" id="status"  class="form-control show-tick" data-live-search="true">
                                <option value="">Status</option>
                                @foreach( $statuses as $status )
                                <option value="{{$status->id}}">{{ $status->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="store" id="store"  class="form-control show-tick" data-live-search="true" >
                                <option value="">Location</option>
                                @foreach( $stores as $store )
                                <option value="{{$store->id}}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="assign"  class="form-control" >
                                <option value="">Assign</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <input type="submit" value="Search" class="btn btn-success">
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('inventories.index') }}" class="btn btn-danger"> Clear </a>
                    </div>
                </div>
                </form>
            </div>
            <div class="body">
                <div class="row card ">
                    <form action="{{ route("inventories.upload.bluck") }}" enctype="multipart/form-data" method="post">
                        @csrf
                    <div class="col-md-4 col-lg-offset-3">

                            <div class="form-group form-float">
                                <label class="form-label">Select a File</label>
                                <div class="">
                                    <input type="file" name="asset_file" class="form-control" required >
                                </div>
                            </div>

                    </div>
                    <div class="col-md-4 p-5" style="padding-top: 20px;">
                        <input type="submit" class="btn btn-success btn-lg custom-btn" value="Upload">
                    </div>
                </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>SN / IMEI</th>
                                <th>Asset Tag</th>
                                <th title="Product Status">Status</th>
                                <th title="Quantity">Qty</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Location / User</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>SN / IMEI</th>
                                <th>Asset Tag</th>
                                <th title="Product Status"> Status</th>
                                <th title="Quantity">Qty</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Location / User</th>
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
                                <td class="{{ $data->status->slug == 'active' ? 'text-success' : 'text-danger'  }}">
                                    {{ $data->status->name }}
                                </td>
                                <td>
                                    @if($data->producttype->slug == 'software')
                                    {{ $data->quantity }},
                                    Assigned - {{ $data->assigned }}
                                    @endif
                                </td>
                                <td>{{ $data->purchase->supplier->company }}</td>
                                <td>{{ date('d-m-Y', strtotime($data->purchase_date)) }} </td>

                                <td>
                                    @if($data->is_assigned == 1)
                                    {{ $data->currentUser->count() > 0 ? $data->currentUser->first()->employee->name." - ". $data->currentUser->first()->employee->emply_id : "" }}
                                    @else
                                    {{ $data->store->name }}
                                    @endif
                                </td>

                                <td>
                                    {{-- <a href=" {{ route('inventories.show', $data->id) }}" class="btn btn-info waves-effect ">
                                        <i class="material-icons">visibility</i>
                                    </a> --}}

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

<script src="{{ asset('backend/select2/select2.min.js') }}"></script>

<script>

    $(document).ready(function() {

        // Initialize Select2
        $('#type').select2();
        $('#status').select2();
        $('#store').select2();

    });

    $("#resetForm").click(function(){
        alert("ok");
        $("#filerForm")[0].reset();
    });



    $(".updateInv").click(function() {
        // toastr.success('Click Button');
        var invId = $(this).data('inv-id');
        var asset = $("#service-" + invId).val();
        var url = location.origin + '/update-asset-tag/' + invId;

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
