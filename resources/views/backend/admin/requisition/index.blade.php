@extends('layouts.backend.app')

@section('title','Admin | Requisitions')

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
    <div class="block-header">
        <a href="{{ route('requisitions.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
    <i class="material-icons">add</i>
    <span>New Requisition </span>
    </a>

</div>
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Inventories
                    <span class="badge ">{{ $requisitions->count() }}</span>
                </h2>
            </div>

            <div class="header">
                <form action="{{ route("requisitions.index") }}" method="GET" id="filerForm">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="type" id="type" class="form-control show-tick" data-live-search="true">
                                <option value="">Product Type</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="status" id="status"  class="form-control show-tick" data-live-search="true">
                                <option value="">Status</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">

                            <select name="store" id="store"  class="form-control show-tick" data-live-search="true" >
                                <option value="">Location</option>

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
                        <a href="{{ route('requisitions.index') }}" class="btn btn-danger"> Clear </a>
                    </div>
                </div>
                </form>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Type</th>
                                <th>Product</th>
                                <th>Department</th>
                                <th title="Quantity">Qty</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Justification</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>SL</th>
                                <th>Type</th>
                                <th>Product</th>
                                <th>Department</th>
                                <th title="Quantity">Qty</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Justification</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach( $requisitions as $key => $data)

                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->type->name }}</td>
                                <td>{{ empty($data->product) ? "" : $data->product->title }}</td>
                                <td>{{ $data->department->name }} </td>
                                <td>{{ $data->quantity }} </td>
                                <td class="{{ $data->status == 'accepted' ? 'text-success' : 'text-danger'  }}">
                                    {{ $data->status }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($data->created_at)) }} </td>
                                <td>{{ $data->description }} </td>
                                <td>{{ $data->justification }} </td>
                                <td>{{ $data->remarks }} </td>
                                <td>
                                    <a href=" {{ route('requisitions.show', $data->id) }}" class="btn btn-info waves-effect ">
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
<script src="{{ asset('backend/select2/select2.min.js') }}"></script>

<script>

    $(document).ready(function() {

        // Initialize Select2
        $('#type').select2();
        $('#status').select2();
        $('#store').select2();

    });


</script>

@endpush
