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
