@extends('layouts.backend.app')

@section('title', 'Admin | Dashboard')

@push('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .orange {
            color: #FF9800 !important;
        }

        .tinfo {
            color: #00BCD4 !important;
        }

        .tdanger {
            color: #F44336 !important;
        }

        .text a {
            color: #fff;
        }

        .info-box {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD</h2>
    </div>

    <div class="row clearfix">

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">wc</i>
                </div>
                <div class="content">
                    <div class="text">Active Employees</div>
                    <div class="number count-to" data-from="0" data-to="{{ $employees->count() }}" data-speed="15"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("employees.index") }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-teal hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">laptop</i>
                </div>
                <div class="content">
                    <div class="text">Total Laptops</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_laptop }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("reports.inventory") . '?type=1' }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">phone_android</i>
                </div>
                <div class="content">
                    <div class="text">Total Mobiles</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_mobile }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("reports.inventory") . '?type=19' }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-blue hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">devices</i>
                </div>
                <div class="content">
                    <div class="text">Total Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("reports.inventory") }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">update</i>
                </div>
                <div class="content">
                    <div class="text">Pending Tag Update</div>
                    <div class="number count-to" data-from="0" data-to="{{ pending_tag() }}" data-speed="15"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("inventories.pending") }}">Details</a>
        </div>
        {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">add_shopping_cart</i>
                </div>
                <div class="content">
                    <div class="text">Purchase <small>In this Month</small> </div>
                    <div class="number count-to" data-from="0" data-to="{{ $purchase }}" data-speed="15"
                        data-fresh-interval="20"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">laptop</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Laptop</div>
                    <div class="number count-to" data-from="0" data-to="{{ $assigned_laptop }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("transections.index") }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-deep-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">phone_android</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Mobiles</div>
                    <div class="number count-to" data-from="0" data-to="{{ $assigned_mobile }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("transections.index") }}">Details</a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-blue hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">devices</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Products</div>
                    <div class="number count-to" data-from="0" data-to="{{ $total_assigned }}" data-speed="1000"
                        data-fresh-interval="20"></div>
                </div>
            </div>
            <a href="{{ route("transections.index") }}">Details</a>
        </div>
    </div>

    <div class="row clearfix">
        <!-- Task Info -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Products will Expire <small>within 90days</small></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th>S.L</th>
                                    <th>Product</th>
                                    <th>Purchase Date</th>
                                    <th>Expired Date</th>
                                    <th>Renew Days Remain</th>
                                    <th>Vendor</th>
                                    <th>Contact </th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expired_product->orderBy('expired_date', 'ASC')->get() as $key => $data)
                                    @php
                                        $datework = Carbon\Carbon::now();
                                        $days = $datework->diffInDays($data->expired_date);

                                        if ($days > 60 && $days <= 90) {
                                            $text = 'tinfo';
                                        } else if ($days > 30 && $days <= 60) {
                                            $text = 'orange';
                                        } else {
                                            $text = 'tdanger';
                                        }
                                    @endphp

                                    @if($data->producttype->slug == 'software')
                                        <tr class="{{ $text }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->product->title }}</td>
                                            <td>{{ $data->purchase_date }}</td>
                                            <td>{{ $data->expired_date }}</td>

                                            <td>{{ $days }} </td>
                                            <td>{{ $data->purchase->supplier->company }}</td>
                                            <td>{{ $data->purchase->supplier->phone }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Task Info -->
    </div>
</div>

@endsection

@push('js')
    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('backend/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/morrisjs/morris.js') }}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>

    <script src="{{ asset('backend/js/pages/index.js') }}"></script>
@endpush
