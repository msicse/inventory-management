@extends('layouts.backend.app')

@section('title', 'Admin | Purchases | Add')

@push('css')
    <!-- Bootstrap Tagsinput Css -->
    <link href="{{ asset("backend/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css") }}" rel="stylesheet">
    <link rel="stylesheet"
        href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />

    <style>
        .d-none {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Update Purchase</h2>
                        <div>
                            <a href="{{ route('purchases.index') }}" class="btn btn-primary waves-effect"
                                style="margin-bottom:10px;">
                                <i class="material-icons">keyboard_return</i>
                                <span>Return</span>
                            </a>
                        </div>
                    </div>
                    <div class="body">
                        <form action="{{ route('purchases.update', $purchase->id) }}" method="post"
                            enctype="multipart/form-data" id="purchase_form">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-6 "> <!-- col-md-offset-3 -->
                                    <div class="form-group ">
                                        <label class="form-label">Select Supplier</label>
                                        <select name="supplier" id="supplier" class="form-control show-tick" required>
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $data)
                                                <option value="{{ $data->id }}" {{ $data->id == $purchase->supplier_id ? 'selected' : '' }}>
                                                    {{ $data->company }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label id="supplier-error" class="error" for="supplier"></label>

                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Invoice No</label>
                                        <input type="text" name="invoice_no" id="invoice_no"
                                            value="{{ $purchase->invoice_no }}" class="form-control" required>
                                    </div>

                                    <input type="hidden" name="serial" id="serial" required value="0">
                                    <input type="hidden" name="license" id="license" required value="0">



                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="form-label">Date of Purchase</label>
                                        <input type="text" name="date_of_purchase"
                                            value="{{ empty(old('date_of_purchase')) ? $purchase->purchase_date : old('date_of_purchase') }}"
                                            class="datepicker form-control" placeholder="Please choose Purchase Date..."
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Challan No</label>
                                        <input type="text" name="challan_no" id="challan_no"
                                            value="{{ $purchase->challan_no }}" class="form-control">
                                    </div>


                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 "> <!-- col-md-offset-3 -->
                                    <div class="form-group form-float">
                                        <label class="form-label">Product Type </label>
                                        <select name="product_type" id="product_type" class="form-control">
                                            <option value="">Select Product Type </option>

                                            @foreach ($types as $data)
                                                <option value="{{ $data->id }}"> {{ $data->name }} </option>
                                            @endforeach
                                        </select>
                                        <label id="product_type-error" class="error" for="product_type"></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="form-label">Select Product</label>
                                        <select name="product" id="product" class="form-control">
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
                                            <tbody id="ptablebody">
                                                @foreach ($purchase->products as $product)
                                                    <tr>
                                                        <td id="{{ $product->product->id }}">{{ $product->product->id }}</td>
                                                        <input type="hidden" class="product_id" name="product_id[]"
                                                            value="{{ $product->product->id }}" />
                                                        <td>
                                                            {{ $product->product->title }}
                                                        </td>
                                                        <td style="width: 15%">
                                                            <input class="price form-control "
                                                                onchange="calculate_single_entry_sum({{ $product->product->id}})"
                                                                type="number" id="single_price_{{$product->product->id}}"
                                                                name="unit_price[]" value="{{$product->unit_price}}">
                                                        </td>
                                                        <td style="width: 10%">
                                                            <input class="form-control quantity"
                                                                id="single_quantity_{{$product->product->id}}"
                                                                onchange="calculate_single_entry_sum({{$product->product->id}})"
                                                                type="number" name="quantity[]" min="1"
                                                                value="{{$product->total_price}}">
                                                        </td>
                                                        <td style="width: 15%">
                                                            <input class="form-control"
                                                                id="single_total_{{$product->product->id}}" type="text"
                                                                name="total[]" value="{{ $product->total_price }}" readonly>
                                                        </td>
                                                        <td style="width: 5%">
                                                            <input class="form-control warranty" type="number" name="month[]"
                                                                value="{{ $product->warranty }}" required>
                                                        </td>
                                                        <td style="width: 25%">
                                                            @if($product->serials)
                                                                @foreach(json_decode($product->serials) as $data)
                                                                    <span class="label label-info">{{ $data }}</span>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td style="width: 5%">
                                                            <button type="button" class="btn btn-danger btn-xs delete"
                                                                onclick="delete_row($(this))">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                        <div style="font-size: 18px; text-align:center">
                                            <strong>Grand Total:</strong> <input type="number" name="grand_total"
                                                id="grand_total" value="{{ $purchase->total_price }}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">

                                <button type="button" id="form_submit"
                                    class="btn btn-success btn-lg custom-btn">Update</button>
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
    <script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">

    </script>
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="{{ asset("backend/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js") }}"></script>
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>


    <script>
        // Delete Row
        function delete_row(row) {
            row.closest('tr').remove();
            calculate_sub_total();
        }

        // Select2 refresh

        function selectRefresh(quantity = 1) {
            $('.serials').select2({
                tags: true,
                width: '100%',
                minimumResultsForSearch: -1,
                maximumSelectionLength: quantity,
                minimumSelectionLength: quantity,
            });
        }

        // Calculate Single Entry

        function calculate_single_entry_sum(entry_number) {
            selectRefresh();
            quantity = parseInt($("#single_quantity_" + entry_number).val());
            // alert(quantity);
            purchase_price = parseFloat($("#single_price_" + entry_number).val());
            // alert(purchase_price);
            single_entry_total = parseFloat(quantity * purchase_price);
            $("#single_total_" + entry_number).val(single_entry_total);

            calculate_sub_total();
            selectRefresh(quantity);
        }


        // Calculate Sub Total
        function calculate_sub_total() {
            var sub_result = 0;
            $("input[id*='single_total_']").each(function () {
                sub_result += parseFloat($(this).val());
            });
            document.getElementById('grand_total').value = sub_result;
        }


        $(document).ready(function () {

            // Initialize Select2
            $('#supplier').select2();
            $('#product').select2();
            $('#product_type').select2();

            $('.serialsInput').tagsinput({
                maxTags: 3
            });

            // Initialize Datepicker
            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            });


            // Get product by product Type
            $('#product_type').change(function (e) {
                e.preventDefault();
                var typeId = $(this).val();
                var url = location.origin + '/purchases/typed/' + typeId;
                if (typeId) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                $('#product').empty();
                                $('#product').append('<option hidden>Select Product</option>');
                                $.each(data, function (key, product) {
                                    $('select[name="product"]').append(
                                        '<option value="' + product.id +
                                        '" data-serialed="' + product.is_serial +
                                        '" data-waranted="' + product.is_license +
                                        '" >' +
                                        product.title + '-' + product.brand + '-' +
                                        product.model + '</option>');
                                });
                            } else {
                                $('#product').empty();
                            }
                        }
                    });
                }
            });

            // Get product info on change product

            $('#product').change(function () {


                let id = $(this).val();
                console.log(id);
                let productTitle = $("#product option:selected").text();
                let isSerialProduct = $("#product option:selected").data("serialed");
                let isWarentyProduct = $("#product option:selected").data("waranted");

                let serialInput = '';
                let warrantyInput = '';
                if (isWarentyProduct === 1) {
                    warrantyInput =
                        `<input class="form-control warranty" type="number" name="month[]" required>`;
                }
                if (isSerialProduct === 1) {
                    serialInput =
                        `<select class="form-control serials" id="setmax_${id}" name="serials-${id}[]" multiple data-minimum-results-for-search="Infinity"></select>`;
                }

                // let table = document.getElementById("ptable");
                // let rowCount = table.rows.length;

                if (document.getElementById(id) == null) {

                    let add_row = '<tr> <td id=' + id + '>' + id + `</td>
                            <input type="hidden" class="product_id" name="product_id[]" value="${id}" />
                            <td>
                                ${productTitle}
                            </td>
                            <td style="width: 15%">
                                <input class="price form-control " onchange="calculate_single_entry_sum(${id})" type="number" id="single_price_${id}" name="unit_price[]">
                            </td>
                            <td style="width: 10%">
                                <input class="form-control quantity" id="single_quantity_${id}" onchange="calculate_single_entry_sum(${id})" type="number" name="quantity[]" min="1" >
                            </td>
                            <td style="width: 15%">
                                <input class="form-control" id="single_total_${id}" type="text" name="total[]" value="" readonly>
                            </td>
                            <td style="width: 5%">
                                ${warrantyInput}
                            </td>
                            <td style="width: 25%">
                                ${serialInput}
                            </td>
                            <td style="width: 5%">
                                <button type="button" class="btn btn-danger btn-xs delete" onclick ="delete_row($(this))">Remove</button>
                            </td>
                            </tr>
                            `;

                    $("#ptable tbody").append(add_row);

                    selectRefresh();
                } else {
                    alert('Alredy Exsist');
                }

            });


            $('#form_submit').click(function (e) {
                // e.preventDefault();
                $("#purchase_form").validate();
                let isValid = true;

                $('.quantity, .price, .warranty').each(function () {

                    if ($(this).val() === '') {
                        isValid = false;
                        alert('All filds are required');
                        return false;
                    }

                });

                $('.serials').each(function () {
                    var quantity;
                    var serialLength;

                    if ($(this).val() === null) {
                        isValid = false;
                        alert('Serials is required');
                        return false;
                    }


                    // if ($(this).hasClass('quantity')){
                    //     quantity = $(this).val();
                    // }

                    // if($(this).hasClass('serials')){
                    //     serialLength = $(this).val().length;
                    // }

                });


                if (isValid) {
                    $("#purchase_form").validate();
                    console.log("submited");
                    $("#purchase_form").submit();

                }


            });







        });
    </script>
@endpush
