@extends('layouts.backend.app')

@section('title','Author | Transections | Add')

@push('css')

<!-- JQuery Select Css -->
<link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">


<style>
    li a span.text {
        padding-left: 30px !important;
    }
    .bs-searchbox input {
        padding-left: 20px !important;
    }
    .bootstrap-select .dropdown-toggle:focus {
        outline: 0 dotted #333333 !important;
        outline: 0 auto -webkit-focus-ring-color !important;
        outline-offset: 0 !important;

    }
    .form-group {
        margin-bottom: 20px !important;
    }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('author.transections.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
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
                        Add New Transection
                        
                    </h2>
                </div>
                <div class="body">
                    <form action="{{ route('author.transections.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label">Select Product</label>
                                    <select name="product" id="product" class="form-control show-tick" data-live-search="true" required>
                                        <option value="">Select Product</option>
                                        @foreach( $stoks as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('product') ? 'selected' : '' }}>{{ $data->product->title  }} {{ $data->product->type == 'laptop' ? "-RSC-".$data->serial_no : "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            
                                <div class="form-group form-float">
                                    <label class="form-label">Quantity</label>
                                    <div class="form-line">
                                        <input type="number" minlength="1" id="quantity" name="quantity" class="form-control" value="1" required onkeyup="calTotal()">
                            
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Date of Issue </label>
                                    <div class="form-line">
                                        <input type="text" name="date_of_issue" value="{{ old('date_of_join') }}" class="datepicker form-control" placeholder="Issue Date...">
                                    </div>
                                </div>
        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label">Select Employee</label>
                                    <select name="employee" id="employee" class="form-control show-tick" data-live-search="true" required>
                                        <option value="">Select Employee</option>
                                        @foreach( $employees as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('employee') ? 'selected' : '' }}>{{ $data->name . " - " .str_pad($data->emply_id, 3, '0', STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Comments</label>
                                    <div class="form-line">
                                        <textarea class="form-control" name="about" rows="5" placeholder="Write Comments Here..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mouse" value="1" id="gridCheck3" >
                                            <label class="form-check-label" for="gridCheck3">
                                                Mouse
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pendrive" value="1" id="gridCheck2">
                                            <label class="form-check-label" for="gridCheck2">
                                                Pendrive
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="laptop_bag" value="1">
                                            <label class="form-check-label" for="gridCheck1">
                                            Laptop Bag
                                            </label>
                                        </div>
                                    </div>
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
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>


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
        format: 'DD-MM-YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });
</script>

@endpush
