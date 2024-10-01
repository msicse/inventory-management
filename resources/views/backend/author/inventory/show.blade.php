@extends('layouts.backend.app')

@section('title','Author | Stocks ')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    
    <style>
        .show-image {
            margin-bottom: 20px;
        }
        .show-image img{
            height: 200px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('author.inventories.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span>
        </a>
    
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>Stock Details</strong>
                        
                    </h2>
                </div>
                <div class="body table-responsive">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <td>{{ $stock->product->title }}</td>
                                <th>Product Type</th>
                                <td>
                                    @if($stock->product->type == 1)
                                        Laptop
                                    @elseif($stock->product->type == 2)
                                        Phone
                                    @else
                                        Pendrive
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $stock->product->model }}</td>
                                <th>Seller Company</th>
                                <td> {{ $stock->purchase->supplier->company }} </td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{ $stock->purchase->unite_price }}</td>
                                <th>Purchase Date</th>
                                <td>{{ $stock->purchase->purchase_date }} </td>
                            </tr>
                            <tr>
                                <th>Serial No.</th>
                                <td>{{ $stock->serial_no }}</td>
                                <th>Service Tag</th>
                                <td> {{ $stock->service_tag }} </td>
                            </tr>
                            <tr>
                                <th>MAC</th>
                                <td>{{ $stock->mac }}</td>
                                <th>Product Status</th>
                                <td>
                                    @if($stock->product_status == 1)
                                    <span class="text-success"> Active </span>
                                    @elseif($stock->product_status == 2)
                                    <span class="text-warning"> Poor </span>
                                    @else
                                    <span class="text-danger"> Damage </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Is Stocked</th>
                                <td>
                                    @if($stock->purchase->is_stocked == 1)
                                    <span class="text-success"> Yes </span>
                                    @else
                                    <span class="text-danger"> No </span>
                                    @endif
                                </td>
                                <th>Is Assigned </th>
                                <td>
                                    @if($stock->is_assigned == 1)
                                    <span class="text-success"> Yes </span>
                                    @else
                                    <span class="text-danger"> No </span>
                                    @endif
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Transection History <strong>{{ $stock->name }}</strong>
                        
                    </h2>
                </div>
                <div class="body table-responsive">
                    
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Issue Date </th>
                                <th>Return Date </th>
                                <th>Quantity</th>
                                
                            </tr>
                            
                        </thead>
                        <tfoot>
                            <th>SL</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Issue Date </th>
                            <th>Return Date </th>
                            <th>Quantity</th>
                        </tfoot>
                        <tbody>
                            @foreach( $stock->transections as $key => $data )
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->employee->name }}</td>
                                <td>{{ $data->employee->designation }}</td>
                                <td>{{ $data->employee->department->name }}</td>
                                <td>{{ $data->issued_date }}</td>
                                <td>{{ $data->return_date }}</td>
                                <td>{{ $data->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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

</script>

@endpush
