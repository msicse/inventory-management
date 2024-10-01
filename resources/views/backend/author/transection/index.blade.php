@extends('layouts.backend.app')

@section('title','Author | Transections')

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
        <a href="{{ route('author.transections.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Transection</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Transections
                        <span class="badge ">{{ $transections->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Product</th>
                                    <th>Serial No</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Issue Date </th>
                                    <th>Return Date </th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                
                                </tr>
                            
                            </thead>
                            <tfoot>
                                <th>SL</th>
                                <th>Product</th>
                                <th>Serial No</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Issue Date </th>
                                <th>Return Date </th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tfoot>
                            <tbody>
                                @foreach( $transections as $key => $data )
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $data->stock->product->title }}</td>
                                    <td>{{ empty($data->stock->serial_no) ? "" : "RSC-".$data->stock->serial_no }}</td>
                                    <td>{{ $data->employee->name }}</td>
                                    <td>{{ $data->employee->designation }}</td>
                                    {{-- <td>{{ $data->employee->department->name }}</td> --}}
                                    <td>{{ $data->issued_date }}</td>
                                    <td>{{ $data->return_date }}</td>
                                    <td>{{ $data->quantity }}</td>
                                    <td>
                                        <a href="{{ route('author.transections.show', $data->id) }}" class="btn btn-info waves-effect " >
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        
                                        <a href="{{ route('settings.policy', $data->id) }}" target="blank" title="Print Policy" class="btn btn-primary waves-effect "  style="margin-top: 5px;">
                                            <i class="material-icons">print</i>
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


@endpush
