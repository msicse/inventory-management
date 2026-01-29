@extends('layouts.backend.app')

@section('title','Admin | Purchases | Add')

@push('css')

<link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
<link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />

<style>
    .d-none {
        display: none;
    }
    /* .bootstrap-tagsinput .label-info{
        text-transform: uppercase;
    } */
  

</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('purchases.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
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
                        Add Purchase
                        
                    </h2>
                </div>
                <div class="body">
                    <form action="{{ route('purchases.store')}}" method="post" enctype="multipart/form-data" id="purchase_form">
                            @csrf
                        <div class="row">
                            <div class="col-md-6 "> <!-- col-md-offset-3 -->
                                <div class="form-group ">
                                    <label class="form-label">Select Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control show-tick" required>
                                        <option value="">Select Supplier</option>
                                        @foreach( $suppliers as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == old('supplier') ? 'selected' : '' }}>{{ $data->company }}</option>
                                        @endforeach
                                    </select>
                                    <label id="supplier-error" class="error" for="supplier"></label>

                                </div>
                                <div class="form-group">
                                    <label class="form-label">Invoice No</label>
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control" required readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Challan No</label>
                                    <input type="text" name="challan_no" id="challan_no" class="form-control" >
                                </div>

                                
                                <input type="hidden" name="serial" id="serial" required value="0">
                                <input type="hidden" name="license" id="license" required value="0">

                            
                                
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="form-label">Date of Purchase</label>
                                    <input type="text" name="date_of_purchase" value="{{ old('date_of_join') }}" class="datepicker form-control" placeholder="Please choose Purchase Date..." required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Reference Invoice No</label>
                                    <input type="text" name="reference_invoice" class="form-control" required>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 "> <!-- col-md-offset-3 -->
                                <div class="form-group form-float">
                                    <label class="form-label">Product Type </label>
                                    <select name="product_type" id="product_type" class="form-control" required >
                                        <option value="">Select Product Type </option>
                            
                                        @foreach( $types as $data)
                                        <option value="{{ $data->id }}"> {{ $data->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">Select Product</label>
                                    <select name="product" id="product" class="form-control" required>
                                        <option value="">Select Product</option>
                                    </select>
                                    <label id="product-error" class="error" for="product"></label>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="ptable">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Product </th>
                                                <th>Unit Price</th>
                                                <th>Qty.</th>
                                                <th>Total </th>
                                                <th>Warranty Period(Month) </th>
                                                <th>Serial </th>
                                            </tr>
                                        </thead>
                                
                                    </table>
                                    <div style="font-size: 18px; text-align:center">
                                        <strong>Grand Total:</strong> <input type="number" name="grand_total" id="grand_total" value="0" readonly  />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            
                            <button type="submit" id="form_submit" class="btn btn-success btn-lg custom-btn" >Save</button>
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


    function selectRefresh(quantity = 1) {
        $('.serials').select2({
            tags: true,
            width: '100%',
            minimumResultsForSearch: -1,
            maximumSelectionLength: quantity,
            minimumSelectionLength: quantity,
        });
    }

    function addRow(tableID,id) {

            var table = document.getElementById(tableID);
            var url=location.origin+'/purchase/'+id;
                        
            $.get(url,function(data){

                var rowCount = table.rows.length;

                if(document.getElementById(id) == null) {

                    var row = table.insertRow(rowCount);

                    for( j=0;j<= rowCount; j++){
                        row.innerHTML = '<td>'+j+'</td>'+data;
                    }


                }
                else
                {
                    alert('Alredy Exsist');
                }
                
            });
    }



    $("#quantity").val();

    $('#form_submit').click(function(){
        

        //  $("select[class*='serials']").each(function(){
        //     sub_result +=parseFloat($(this).val());
        // });
        
        
        let serials = $('#serials').val();

        if( serials == null ){
            $("#purchase_form").validate();
            $('#purchase_form').submit();

        }else {
            let qty = $("#quantity").val();
            
            let count = serials.length;
            if( count == qty){
                $('#purchase_form').submit();

            }else{
                alert('Quantity and Serials not match');
            }

        }

    });

    

    function calTotal(){

        quantity = $("#quantity").val();

        var unit_price = $("#unit_price").val();

        var  total_price = unit_price * quantity;
        var total_price = Math.floor(total_price);

        $('#total_price').val(total_price);

    }


    $('#product_type').change(function(e){
        e.preventDefault();
        console.log('ok');
        var typeId = $(this).val();
        var url = location.origin + '/purchases/typed/' + typeId;
        if(typeId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data)
                {
                    console.log(data);
                    if(data){
                        $('#product').empty();
                        $('#product').append('<option hidden>Select Product</option>'); 
                        $.each(data, function(key, product){
                            $('select[name="product"]').append('<option value="'+ product.id +'">' +product.title+ '-'+ product.brand +'-'+ product.model +'</option>');
                        });
                    }else{
                        $('#product').empty();
                    }
                }
            });
        }
    });

    function calculate_single_entry_sum(entry_number)
    {
        selectRefresh();
        quantity = parseInt($("#single_quantity_"+entry_number).val());
        // alert(quantity);
        purchase_price      = parseFloat($("#single_price_"+entry_number).val());
        // alert(purchase_price);
        single_entry_total  = parseFloat(quantity * purchase_price);
        $("#single_total_"+entry_number).val(single_entry_total);


        // var a = $('#mydiv').data('myval'); //getter

        // $('#setmax_'+entry_number).data('maximum-selection-length',20); //setter
        
        // $('#setmax_'+entry_number).attr({ 'data-maximum-selection-length': quantity });
        
        calculate_sub_total();
        selectRefresh(quantity);

    }

    function calculate_sub_total()
    {
        var sub_result = 0;
        $("input[id*='single_total_']").each(function(){
            sub_result +=parseFloat($(this).val());
        });


        document.getElementById('grand_total').value = sub_result;
        console.log(sub_result);

    }

    $('#product').change(function(){

        let id = $(this).val();
        let productTitle = $( "#product option:selected" ).text();;
        let table = document.getElementById("ptable");
        let rowCount = table.rows.length;

        if(document.getElementById(id) == null) {

            var row = table.insertRow(rowCount);

            for( j=0;j<= rowCount; j++){
                row.innerHTML = '<td id='+id+'>'+j+`</td>
                <input type="hidden" name="product_id[]" value="${id}" />
                <td>
                    ${productTitle}
                </td>
                <td style="width: 15%">
                    <input class="price form-control" onchange="calculate_single_entry_sum(${id})" type="number" id="single_price_${id}" name="unit_price[]" value="0" min="0" step="0.01" required>
                </td>
                <td style="width: 10%">
                    <input class="form-control" id="single_quantity_${id}" onchange="calculate_single_entry_sum(${id})" type="number" name="quantity[]" min="1" required>
                </td>
                <td style="width: 15%">
                    <input class="form-control" id="single_total_${id}" type="text" name="total[]" value="" readonly>
                </td>
                <td style="width: 5%">
                    <input class="form-control" type="number" name="month[]" required >
                </td>
                <td style="width: 25%">
                    <select class="form-control serials" id="setmax_${id}" name="serials-${id}[]" multiple data-minimum-results-for-search="Infinity"></select>
                </td>
                <td style="width: 5%">
                    <button type="button" class="btn btn-danger btn-xs delete" onclick ="delete_row($(this))">Remove</button>
                </td>
                `;
            }
            
            selectRefresh();


        }
        else
        {
            alert('Alredy Exsist');
        }


        var url = location.origin + '/purchases/product/' + id;

        $.get(url, function(data) {

            if(data['is_serial'] == 1){
                $('#sl-show').removeClass('d-none');
                $('#serial').val(1);
                $('#serials').attr('required', true);
            }else {
                $('#sl-show').addClass('d-none');
                $('#serial').val(0);
                $('#serials').attr('required', false);

            }

        });
        
    });

    function delete_row(row)
    {
        row.closest('tr').remove();
        calculate_sub_total();
    }
    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });




    function calDate(event){
        var dt = $('#date_of_purchase').val();
        var warenty = parseInt($('#month').val());

        var date_of_purchase = new Date(dt);

        if(warenty > 0){
            date_of_purchase.setMonth(date_of_purchase.getMonth() + warenty);

            const yyyy = date_of_purchase.getFullYear();
            let mm = date_of_purchase.getMonth() + 1; // Months start at 0!
            let dd = date_of_purchase.getDate();

            if (dd < 10) dd='0' + dd; 
            if (mm < 10) mm='0' + mm; 
            const formattedToday=yyyy + '-' + mm + '-' + dd; 
            $('#expired_date').val(formattedToday);
        }


    }



    $(document).ready(function() {


        var invUrl=location.origin+'/invoice';
        $.get(invUrl,function(data){

            $("#invoice_no").val(data);
            console.log(data);
        });

        $('.serials').select2({
            tags: true,
            width: '100%',
            minimumResultsForSearch: -1,
            // maximumSelectionLength: quantity,
        });

        $('#supplier').select2();
        $('#product').select2();
        $('#product_type').select2();


    });

    





</script>

@endpush
