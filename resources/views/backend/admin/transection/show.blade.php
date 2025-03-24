@extends('layouts.backend.app')

@section('title','Distribution | Show ')

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
        <a href="{{ route('transections.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span>
        </a>

    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


            <div class="card">
                <div class="header">
                    <h2>
                        <strong>Employee Details</strong>

                    </h2>
                </div>
                <div class="body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <img src="{{ asset('images/employee/'. $transection->employee->image) }}" style="height: 120px;" alt="">
                                </td>
                            </tr>
                            <tr>
                                <th>Employee Name</th>
                                <td colspan="3">{{ $transection->employee->name}}</td>
                            </tr>
                            <tr>
                                <th>Employee ID</th>
                                <td>{{ $transection->employee->emply_id}}</td>
                                <th>Designation </th>
                                <td>{{ $transection->employee->designation }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $transection->employee->department->name }}</td>
                                <th>Employee Status</th>
                                <td> {!! $transection->employee->status == 1 ? "<span class=text-success>Active</span>" : "<span class=text-danger>Inactive</span>" !!}</td>
                            </tr>

                            <tr>
                                <th>Phone</th>
                                <td>{{ $transection->employee->phone }}</td>
                                <th>Email</th>
                                <td> {{ $transection->employee->email }} </td>
                            </tr>
                            <tr>
                                <th>Date of Join</th>
                                <td>{{ formatDate($transection->employee->date_of_join) }}</td>
                                <th>Date of Resign</th>
                                <td>{{ formatDate($transection->employee->resign_date) }} </td>
                            </tr>

                        </thead>
                    </table>
                </div>
                <div class="card">
                    <div class="header">
                        <h2>
                            <strong>Distribution Details</strong>

                        </h2>
                    </div>
                    <div class="body table-responsive">

                        <table class="table table-bordered">
                            <thead>

                                <tr>
                                    <th>Issue Date</th>
                                    <td>{{ $transection->issued_date}}</td>
                                    <th>Return Date</th>
                                    <td>{{ $transection->return_date }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td>{{ $transection->quantity}}</td>
                                    <th>In Servicing</th>
                                    <td>{{ $transection->stock->store->slug == 'admin-servicings' ? "Yes / ".$transection->stock->store->name : "No"}}</td>
                                </tr>
                                <tr>
                                    <th>Comments</th>
                                    <td colspan="3">{{ $transection->comment }}</td>
                                </tr>

                            </thead>
                        </table>
                    </div>
                </div>
            <div class="card">
                <div class="header">
                    <h2>
                        <strong>Product Details</strong>

                    </h2>
                </div>
                <div class="body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <td>{{ $transection->stock->product->title }}</td>
                                <th>Product Type</th>
                                <td>
                                    {{ $transection->stock->product->type->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $transection->stock->product->model }}</td>
                                <th>Seller Company</th>
                                <td> {{ $transection->stock->purchase->supplier->company }}  </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th>Serial No.</th>
                                <td>{{ $transection->stock->service_tag }}</td>
                                <th>Asset Tag</th>
                                <td> {{ $transection->stock->asset_tag }} </td>
                            </tr>
                            <tr>
                                <th>IMEI</th>
                                <td>{{ $transection->stock->product->type->slug == 'mobile' ? $transection->stock->service_tag : '' }}</td>
                                <th>MAC</th>
                                <td>{{ $transection->stock->mac }}</td>

                            </tr>
                            <tr>
                                <th>Product Condition</th>
                                <td>
                                    {{ $transection->stock->asset_condition }}
                                </td>
                                <th>Purchase Date</th>
                                <td>{{ formatDate($transection->stock->purchase->purchase_date) }}</td>

                            </tr>
                            <tr>
                                <th> {{ $transection->stock->product->type->slug == 'software' ? 'Subscription' : 'Expired' }} Date</th>
                                <td>{{ formatDate($transection->stock->purchase->expired_date) }}</td>
                                <th> {{ $transection->stock->product->type->slug == 'software' ? 'Subscription' : 'Warrenty' }} (days)</th>
                                <td>{{ $transection->stock->purchase->warranty }}</td>
                            </tr>

                        </thead>
                    </table>
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


@endpush
