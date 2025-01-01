@extends('layouts.backend.app')

@section('title','Distribution')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

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
        <a href="{{ route('transections.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Distribution</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Distribution List
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
                                    <th>Type</th>
                                    
                                    <th>Serial No</th>
                                    <th>Employee Name & ID</th>
                                    <th>Designation</th>
                                    <th>Issue Date </th>
                                    <th>Return Date </th>
                                    <th>Qty.</th>
                                    <th>Action</th>
                                
                                </tr>
                            
                            </thead>
                            <tfoot>
                                <th>SL</th>
                                
                                <th>Product</th>
                                <th>Type</th>
                                <th>Serial No</th>
                                <th>Employee Name & ID</th>
                                <th>Designation</th>
                                <th>Issue Date </th>
                                <th>Return Date </th>
                                <th>Qty.</th>
                                <th>Action</th>
                            </tfoot>
                            <tbody>
                                @foreach( $transections as $key => $data )
                                
                                <tr>
                                <form action="{{ route('transections.update', $data->id) }}" method="post" >
                                        @csrf
                                        @method('PUT')
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $data->stock->product->title }}</td>
                                    <td>{{ $data->stock->producttype->name }}</td>
                                    <td> {{ $data->stock->service_tag  }}</td>
                                    <td>{{ $data->employee->name .' - '. sprintf('%03d', $data->employee->emply_id) }}</td>
                                    <td>{{ $data->employee->designation }}</td>
                                    {{-- <td>{{ $data->employee->department->name }}</td> --}}
                                    <td>{{ $data->issued_date }}</td>
                                    <td>
                                        @empty($data->return_date)
                                        <input type="text" name="date_of_return" value="{{ old('date_of_return') }}" class="datepicker form-control" style="width: 100px;" placeholder="Return Date...">
                                        @else
                                        {{ $data->return_date }}
                                        @endempty

                                    </td>
                                    <td>{{ $data->quantity }}</td>
                                    <td>
                                        <a href="{{ route('transections.show', $data->id) }}" class="btn btn-info waves-effect " style="margin-top: 5px;" >
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        
                                        <!-- <a href="{{ route('settings.policy', $data->id) }}" target="blank" title="Print Policy" class="btn btn-primary waves-effect "  style="margin-top: 5px;">
                                            <i class="material-icons">print</i>
                                        </a> -->

                                        <a href="{{ route('transections.ack', $data->id) }}" target="blank" title="Print Acknowledgement" class="btn btn-primary waves-effect "  style="margin-top: 5px;">
                                            <i class="material-icons">print</i>
                                        </a>
                                        
                                        @if ($data->return_date !== null)
                                            <a href="{{ route('transections.return', $data->id) }}"
                                                target="blank" title="Print Return Form"
                                                class="btn btn-warning waves-effect " style="margin-top: 5px;">
                                                <i class="material-icons">print</i>
                                            </a>
                                        @endif
                                        
                                        @empty($data->return_date)
                                        <button type="submit"  title="Update Return Date " class="btn btn-success waves-effect" style="margin-top: 5px;">
                                            <i class="material-icons">update</i>
                                        </button>
                                        @endempty
                                        

                                       
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



    <!-- Moment Plugin Js -->
    <script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>


<script>

$( ".delete" ).click(function() {
    var data_id=$(this).data('delete-id');
    var url=location.origin+'/admin/employees/status/'+data_id;
    $('.delete_form').attr('action',url);

});

$('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD-MM-YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

</script>


@endpush
