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
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Purchase Information</h2>
                    <div>
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
                            <i class="material-icons">keyboard_return</i>
                            <span>Return</span>
                        </a>
                    </div>
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
                                        <button class="btn btn-success waves-effect" title="Add to Inventory" onclick="if(confirm('Are You sure to Add the Products to Inventory?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{ $product->id }}').submit();
                                            } else {
                                            event.preventDefault();
                                            }">

                                                <i class="material-icons">add</i>
                                            </button>

                                            <form id="delete-form-{{ $product->id }}" style="display: none;"
                                                action="{{  route('purchases.inventory',$product->id) }}" method="post">
                                                @csrf


                                            </form>
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

</script>

@endpush
