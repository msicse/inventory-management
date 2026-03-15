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
    .mode-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 4px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .mode-fixed { background: #607d8b; }
    .mode-consumable { background: #ff9800; }
    .mode-unknown { background: #9e9e9e; }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Add New Distribution
                        <span class="pull-right text-danger" id="stocksInfo"></span>
                    </h2>
                    <a href="{{ route('transections.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
                        <i class="material-icons">keyboard_return</i>
                        <span>Return</span>
                    </a>
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
                                    <label class="form-label">Asset Mode</label>
                                    <div>
                                        <span id="assetModeBadge" class="mode-badge mode-unknown">Select Product</span>
                                        <small id="issueHelpText" class="text-muted" style="display:block; margin-top:6px;"></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Issue Quantity</label>
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
                                        <div class="form-check" id="printAckWrap">
                                            <input class="form-control form-check-input" type="checkbox" name="print_ack" value="1" id="print_ack">
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
                        <input id="is_consumable" type="hidden" value="0">
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
        validateIssueQuantity();

    }

    function updateModeBadge(isConsumable) {
        let badge = $('#assetModeBadge');
        if (isConsumable) {
            badge.removeClass('mode-fixed mode-unknown').addClass('mode-consumable').text('Consumable');
            $('#print_ack').prop('checked', false);
            $('#printAckWrap').hide();
        } else {
            badge.removeClass('mode-consumable mode-unknown').addClass('mode-fixed').text('Fixed Asset');
            $('#printAckWrap').show();
        }
    }

    function validateIssueQuantity() {
        let qty = parseInt($('#quantity').val(), 10);
        let available = parseInt($('#remain').val(), 10);

        if (isNaN(qty) || qty <= 0) {
            return;
        }

        if (!isNaN(available) && available >= 0 && qty > available) {
            $('#quantity').val(available > 0 ? available : 1);
            toastr.warning('Issue quantity cannot exceed available stock.');
        }
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
                                $('select[name="product"]').append('<option value="'+ course.id +'" data-is-consumable="'+ (course.is_consumable == 1 ? 1 : 0) +'" data-available="'+ (course.quantity || 0) +'">' + course.title +'-'+ course.brand + ' - '+ course.model+'</option>');

                            }else {
                                $('select[name="product"]').append('<option value="'+ course.id +'" data-is-consumable="'+ (course.is_consumable == 1 ? 1 : 0) +'" data-available="'+ (course.quantity || 0) +'">' + course.title +' - '+ (course.asset_tag || 'N/A') + ' - '+ (course.service_tag || 'N/A') +'</option>');
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
        let selected = $('#product option:selected');
        let isConsumable = parseInt(selected.data('is-consumable') || 0, 10) === 1;
        $('#is_consumable').val(isConsumable ? '1' : '0');
        updateModeBadge(isConsumable);

        if (isConsumable) {
            let available = parseInt(selected.data('available') || 0, 10);
            $('#remain').val(available);
            $('#issueHelpText').text('Consumable distribution supports partial returns.');
            $('#stocksInfo').html('Available Qty: ' + available);
            validateIssueQuantity();
            return;
        }

        if(typeName == 'software'){
            var stockId = $(this).val();
            var url = location.origin + '/single-stock/' + stockId;

            $.get(url, function(data) {
                let remain = data['quantity'] - data['assigned'];
                $('#remain').val(remain);
                $('#issueHelpText').text('Software quantity is license based.');
                $("#stocksInfo").html("Licence Remain: "+ remain);
                validateIssueQuantity();
            });
        } else {
            $('#issueHelpText').text('Fixed asset issuance is tracked per assignment.');
            $('#remain').val('');
            $("#stocksInfo").html("");
        }
    });

    $('#quantity').on('input', function() {
        validateIssueQuantity();
    });


    $(document).ready(function() {
        $('#product_type').select2();
        $('#product').select2();
        $('#employee').select2();
        $('#printAckWrap').show();
    });
    $("#store_form").validate();


</script>

@endpush
