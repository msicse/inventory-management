@extends('layouts.backend.app')

@section('title','Admin | Purchases | Show')

@push('css')
    <style>
        .show-image {
            margin-bottom: 20px;
        }
        .show-image img{
            height: 200px;
        }

        /* Button styling improvements */
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            line-height: 1.4;
            border-radius: 3px;
        }

        .btn .material-icons {
            font-size: 16px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #45a049;
            border-color: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .badge .material-icons {
            vertical-align: middle;
            margin-right: 3px;
        }

        .badge-success {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .header .pull-right {
            margin-top: -5px;
        }

        /* QR Code Section Styling */
        .bg-light-green {
            background-color: #C8E6C9 !important;
        }

        .bg-blue {
            background-color: #BBDEFB !important;
        }

        .alert {
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
        }

        .alert-info {
            background-color: #d9edf7;
            border-color: #bce8f1;
            color: #31708f;
        }

        .card .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .card .header .material-icons {
            vertical-align: middle;
            margin-right: 8px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Purchase Information</h2>
                    <div class="pull-right" style="margin-bottom: 10px;">
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary btn-sm waves-effect">
                            <i class="material-icons">keyboard_return</i>
                            <span>Return</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Invoice No.</th>
                                <td>{{ $purchase->invoice_no }}</td>
                                <th>Reference Invoice No.</th>
                                <td>{{ $purchase->reference_invoice }} </td>
                            </tr>
                            <tr>
                                <th>Challan No.</th>
                                <td>{{ $purchase->challan_no }}</td>
                                <th>Date of Purchase</th>
                                <td>{{ $purchase->purchase_date }}</td>

                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>{{ $purchase->total_price }}</td>
                                <th>Supplier</th>
                                <td>{{ $purchase->supplier->company }}</td>
                            </tr>
                            <tr>
                                <th>Contact Person</th>
                                <td>{{ $purchase->supplier->name }}</td>
                                <th>Phone</th>
                                <td>{{ $purchase->supplier->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $purchase->supplier->email }}</td>
                                <th>Address</th>
                                <td> {{ $purchase->supplier->address }} </td>
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
                        Products
                    </h2>
                </div>
                <div class="body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Serial No</th>
                                <th>Warranty</th>
                                <th>Expired Date</th>
                                <th>Is Stocked</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $purchase->products as $key => $product )
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $product->product->title }}</td>
                                <td>{{ $product->product->type->name }}</td>
                                <td>{{ $product->unit_price }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->total_price }}</td>
                                <td>{{ $product->serials }}</td>
                                <td>{{ $product->warranty }}</td>
                                <td>{{ $product->expired_date }}</td>
                                <td>{{ $product->is_stocked == 1 ? "Yes" : "No" }}</td>
                                <td>
                                    @if($product->is_stocked == 2)
                                        <button class="btn btn-success btn-sm waves-effect"
                                                title="Approve to Inventory"
                                                onclick="confirmAddToInventory({{ $product->id }})">
                                            Approve
                                        </button>

                                        <form id="delete-form-{{ $product->id }}" style="display: none;"
                                              action="{{ route('purchases.inventory', $product->id) }}" method="post">
                                            @csrf
                                        </form>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="material-icons" style="font-size: 14px;">check</i>
                                            Approved
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Code Section --}}
    @php
        $allApproved = $purchase->products->every(function($product) {
            return $product->is_stocked == 1;
        });
        $hasProducts = $purchase->products->count() > 0;
    @endphp

    @if($hasProducts && $allApproved)
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <i class="material-icons">qr_code</i>
                        QR Code Generation
                    </h2>
                </div>
                <div class="body">
                    <div class="alert alert-success" role="alert">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">check_circle</i>
                        <strong>All products have been approved!</strong> You can now generate QR codes for all items in this purchase.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="header bg-light-green">
                                    <h2><i class="material-icons">description</i> Standard QR Codes (A4)</h2>
                                </div>
                                <div class="body">
                                    <p>Generate multiple QR codes on a single A4 page with product information.</p>
                                    <a href="{{ route('purchase.print.qrcodes', $purchase->id) }}"
                                       class="btn btn-success waves-effect"
                                       target="_blank">
                                        <i class="material-icons">print</i>
                                        Print QR Codes (A4)
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="header bg-blue">
                                    <h2><i class="material-icons">label</i> QR Code Labels (1.4")</h2>
                                </div>
                                <div class="body">
                                    <p>Generate individual 1.4" x 1.4" square labels for each item, perfect for printing on label sheets.</p>
                                    <a href="{{ route('purchase.print.qrcode.labels', $purchase->id) }}"
                                       class="btn btn-primary waves-effect"
                                       target="_blank">
                                        <i class="material-icons">label</i>
                                        Print QR Labels (1.4")
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header bg-orange">
                                    <h2><i class="material-icons">view_module</i> QR Code + Barcode Combo Labels (1.4" x 2.5")</h2>
                                </div>
                                <div class="body">
                                    <p>Generate combo labels with QR code on the left and barcode with serial number on the right. Vertically centered on full-height labels, perfect for dual scanning needs.</p>
                                    <a href="{{ route('purchase.print.qrcode.barcode.combo.labels', $purchase->id) }}"
                                       class="btn btn-warning waves-effect"
                                       target="_blank">
                                        <i class="material-icons">view_module</i>
                                        Print QR + Barcode Combo Labels (1.4" x 2.5")
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">info</i>
                                <strong>Note:</strong> QR codes will contain professional asset information including organization details, serial numbers, product types, and asset tags.
                                <br><br>
                                <a href="{{ route('purchase.debug.qrcodes', $purchase->id) }}" class="btn btn-warning btn-sm" target="_blank">
                                    <i class="material-icons">bug_report</i> Debug QR Generation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('js')
<script>
function confirmAddToInventory(productId) {
    if (confirm('Are you sure you want to add this product to inventory?')) {
        document.getElementById('delete-form-' + productId).submit();
    }
}
</script>
@endpush
