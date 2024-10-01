@extends('layouts.backend.app')

@section('title','Author | purchases | Show ')

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
        <a href="{{ route('author.purchases.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span>
        </a>
    
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>Purchase Details</strong>
                        
                    </h2>
                </div>
                <div class="body table-responsive">

                    <table class="table table-bordered">
                    
                        <h5>Purchase Info</h5>
                        <tr>
                            <th>Date of Purchase</th>
                            <td>{{ $purchase->purchase_date }}</td>
                            <th>Quantity</th>
                            <td>{{ $purchase->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Unite Price</th>
                            <td>{{ $purchase->unite_price }}</td>
                            <th>Total Price</th>
                            <td>{{ $purchase->total_price }}</td>
                        </tr>
                        <tr>
                            <th>Is Stocked</th>
                            <td colspan="3">
                                @if($purchase->is_stocked == 1)
                                <span class="text-success"> Yes </span>
                                @else
                                <span class="text-danger"> No </span>
                                @endif
                            </td>
                        </tr>
                      
                    </table>


                    <table class="table table-bordered">

                        <h5>Supplier Details</h5>
                        <tr>
                            <th>Company</th>
                            <td>{{ $purchase->supplier->company }}</td>
                            <th>Contact Person</th>
                            <td>{{ $purchase->supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $purchase->supplier->phone }}</td>
                            <th>Email</th>
                            <td>{{ $purchase->supplier->email }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $purchase->supplier->address }}</td>
                            <th>Description </th>
                            <td>{{ $purchase->supplier->description }}</td>
                        </tr>
                    </table>
                    
                    <table class="table table-bordered">
                        <h5>Product Details</h5>

                        <tr>
                            <th>Product</th>
                            <td>{{ $purchase->product->title }}</td>
                            <th>Product Type</th>
                            <td> {{ $purchase->product->type }} </td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td>{{ $purchase->product->model }}</td>
                            <th>Description</th>
                            <td> {{ $purchase->product->description }} </td>
                        </tr>

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
