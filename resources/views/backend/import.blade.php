@extends('layouts.backend.app')

@section('title', 'Admin | Inventories | Pending Tag')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
        rel="stylesheet">
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
            <a href="{{ route('inventories.create') }}" class="btn btn-primary waves-effect pull-right"
                style="margin-bottom:10px;">
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
                            Import Data From CSV
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row card ">
                            <div class="col-md-4">

                                <label class="form-label">Download Sample CSV:</label>
                                <ul>
                                    <li><a href="{{ asset('backend/samples/product_sample.xlsx') }}" download>Product Sample</a></li>
                                    <li><a href="{{ asset('backend/samples/purchase_product_sample.csv') }}" download>Purchase
                                            Product Sample</a></li>
                                    <li><a href="{{ asset('backend/samples/inventory_sample.csv') }}" download>Inventory Sample</a>
                                    </li>
                                    <li><a href="{{ asset('backend/samples/transection_sample.csv') }}" download>Transection
                                            Sample</a></li>
                                    <li><a href="{{ asset('backend/samples/employee_sample.csv') }}" download>Employee Sample</a>
                                    </li>
                                </ul>
                            </div>
                            <form action="{{ route("imports.store") }}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="col-md-4" style="padding: 20px;">

                                    <div class="form-group form-float">

                                        <select name="import_table" id="type" class="form-control show-tick"
                                            data-live-search="true" required>
                                            <option value="">Select Table</option>
                                            <option value="product">Product</option>
                                            <option value="purchase_product">Purchase Product</option>
                                            <option value="inventory">Inventory</option>
                                            <option value="transection">Transection</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">Select a File</label>
                                        <div class="">
                                            <input type="file" name="csv_file" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="text-center" style="padding-top: 20px;">
                                        <input type="submit" class="btn btn-success btn-lg custom-btn" value="Upload">
                                    </div>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>

@endsection
