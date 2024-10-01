@extends('layouts.backend.app')

@section('title','Distribution | Add')

@push('css')

<!-- JQuery Select Css -->
<link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

<link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
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
        <a href="{{ route('transections.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
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
                        Add New Distribution
                        <span class="pull-right text-danger" id="stocksInfo"></span>
                    </h2>
                </div>
                <div class="body">
                    <form action="{{ route('transections.store')}}" method="post" id="store_form" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="form-label">Product type </label>
                                    <select name="product_type" id="product_type" data-typename="" class="form-control" required >
                                        <option value="">Select Product Type </option>
                            
                                        @foreach( $types as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('product_type') ? 'selected' : '' }}> {{ $data->name }} </option>
                                        @endforeach
                                    </select>
                                    <label id="product_type-error" class="error" for="product_type"></label>
                                </div>

                                <div class="form-group form-float">
                                    <label for="product" class="form-label">Product </label>
                                    <select class="form-control" name="product" id="product" required></select>
                                    <label id="product-error" class="error" for="product"></label>
                                </div>
                            
                                <div class="form-group">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" minlength="1" id="quantity" name="quantity"  class="form-control" value="{{ old('quantity') ? old('quantity') : 1 }}" required onkeyup="calTotal()">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Date of Issue </label>
                                    <input type="text" name="date_of_issue" value="{{ old('date_of_issue') }}" class="datepicker form-control" placeholder="Issue Date..." required>
                                </div>
        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> Employee</label>
                                    <select name="employee" id="employee" class="form-control show-tick" required>
                                        <option value="">Select Employee</option>
                                        @foreach( $employees as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('employee') ? 'selected' : '' }}>{{ $data->name. ' - '.  sprintf('%03d', $data->emply_id) }}</option>
                                        @endforeach
                                    </select>
                                    <label id="employee-error" class="error" for="employee"></label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Comments</label>
                                    <div class="form-line">
                                        <textarea class="form-control" name="comment" rows="5" placeholder="Write Comments Here...">{{ old('comment') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-4" style="padding-top: 20px;">
                                        <div class="form-check">
                                            <input class="form-control form-check-input" type="checkbox" name="print_ack" value="1" id="print_ack" checked>
                                            <label class="form-check-label" for="print_ack">
                                                <strong>Print ACK</strong> 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
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
                                    
                                </div> -->

                            </div>
                        </div>
                        <input id="typename" type="hidden">
                        <input id="remain" type="hidden">
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
<script src="{{ asset('backend/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>


<script>




    //
    function calTotal(){
        //alert('ok');

        var quantity  = $("#quantity").val();
        var unitPrice = $("#unit_price").val();
        let typeName = $('#typename').val();
        let remain = $('#remain').val();
        if(typeName == 'software'){
            $('#quantity').attr('maxlength', remain);
        }
        var total = quantity * unitPrice;

        $('#total_price').val(total);

    }
    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD-MM-YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

    $('#product_type').change(function(e){
        e.preventDefault();

        var typeId = $(this).val();
        var url = location.origin + '/typed-products/' + typeId;
        if(typeId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data)
                {
                    if(data){
                        $('#product').empty();
                        $('#product').append('<option hidden>Select Product</option>');

                        $('#typename').val(data.type);
                        $.each(data.products, function(key, course){

                            if(course.slug == 'software'){
                                $('select[name="product"]').append('<option value="'+ course.id +'">' + course.title +'-'+ course.brand + ' - '+ course.model+'</option>');
                                
                            }else {
                                $('select[name="product"]').append('<option value="'+ course.id +'">' + course.title +' - RSC-'+ course.serial_no + ' - '+ course.service_tag+'</option>');
                                $("#stocksInfo").html("");
                            }
                        });
                    }else{
                        $('#product').empty();
                    }
                }
            });
        }

    });


    $('#product').change(function(e){

        let typeName = $('#typename').val();
        
        if(typeName == 'software'){
            var stockId = $(this).val();
            var url = location.origin + '/single-stock/' + stockId;

            $.get(url, function(data) {
                let remain = data['quantity'] - data['assigned'];
                $('#remain').val(remain);
                $("#stocksInfo").html("Licence Remain: "+ remain);
            });
        }
    });


    $(document).ready(function() {
        $('#product_type').select2();
        $('#product').select2();
        $('#employee').select2();
    });
    $("#store_form").validate();

    
</script>

@endpush
