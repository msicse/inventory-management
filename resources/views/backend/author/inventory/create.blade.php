@extends('layouts.backend.app')

@section('title','Author | Employees | Add')

@push('css')

<!-- JQuery Select Css -->
<link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('author.employees.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span> 
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Add New Employee
                        
                    </h2>
                </div>
                <div class="body">
                    <form action="{{ route('author.purchases.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group form-float">
                                    <label class="form-label">Select Product</label>
                                    <select name="product" id="product" class="form-control show-tick" required>
                                        <option value="">Select Product</option>
                                        @foreach( $products as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('product') ? 'selected' : '' }}>{{ $data->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Select Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control show-tick" required>
                                        <option value="">Select Supplier</option>
                                        @foreach( $suppliers as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('department') ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Unit Price</label>
                                    <div class="form-line">
                                        <input type="number" id="unit_price" name="unit_price" class="form-control" value="{{ old('unit_price') }}" onkeyup="calTotal()" required>
                            
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Quantity</label>
                                    <div class="form-line">
                                        <input type="number" minlength="1" id="quantity" name="quantity" class="form-control" value="1" required onkeyup="calTotal()">
                            
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Total Price</label>
                                    <div class="form-line">
                                        <input type="text" id="total_price" name="total_price" class="form-control" value="{{ old('total_price') }}" required disabled>
                            
                                    </div>
                                </div>       

                                
                                
                                <div class="form-group">
                                    <label class="form-label">Date of Purchase</label>
                                    <div class="form-line">
                                        <input type="text" name="date_of_purchase" value="{{ old('date_of_join') }}" class="datepicker form-control" placeholder="Please choose Joining date...">
                                    </div>
                                </div>
        
                            </div>
                        </div>
                        <div class="row text-center">
                            <input type="submit" class="btn btn-success btn-lg custom-btn" value="Save">
                        </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>

</div>


@endsection

@push('js')
<!-- Moment Plugin Js -->
<script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
<script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>


<script>

    //
    function calTotal(){
        //alert('ok');
        var quantity  = $("#quantity").val();
        var unitPrice = $("#unit_price").val();
        var total = quantity * unitPrice;
        //var total = parseInt(quantity) * parseFloat(unitPrice);
        //var total = parseInt(quantity) + parseInt(unitPrice);

        $('#total_price').val(total);

    }
    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD-mm-YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });
</script>

@endpush
