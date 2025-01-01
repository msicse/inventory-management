@extends('layouts.backend.app')

@section('title','Admin | Requisition | Add')

@push('css')
<link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('employees.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>New Requisition </h2>
                </div>
                <div class="body">
                    <form action="{{ route('requisitions.store')}}" method="post">
                            @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label">Product Type </label>
                                    <select name="product_type" id="product_type" class="form-control" required>
                                        <option value="">Select Product Type </option>

                                        @foreach ($types as $data)
                                            <option value="{{ $data->id }}"> {{ $data->name }} </option>
                                        @endforeach
                                    </select>
                                    <label id="product_type-error" class="error" for="product_type"></label>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">Select Product</label>
                                    <select name="product" id="product" class="form-control">
                                        <option value="0">Select Product</option>
                                    </select>
                                    <label id="product-error" class="error" for="product"></label>

                                </div>

                                <div class="form-group form-float">
                                    <label class="form-label">Quantity</label>
                                    <div class="form-line">
                                        <input type="text" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <div class="form-line">
                                        <textarea class="form-control" name="description" rows="5" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group form-float">
                                    <label class="form-label">Department </label>
                                    <select name="department_id" id="department_id" class="form-control" required>
                                        <option value="">Select Product Type </option>

                                        @foreach ($departments as $data)
                                            <option value="{{ $data->id }}"> {{ $data->name }} </option>
                                        @endforeach
                                    </select>
                                    <label id="department_id-error" class="error" for="department_id"></label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Justification</label>
                                    <div class="form-line">
                                        <textarea class="form-control" name="justification" rows="5" placeholder=""></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Remarks</label>
                                    <div class="form-line">
                                        <textarea class="form-control" name="remarks" rows="5" placeholder=""></textarea>
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

    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>

<script>

      $(document).ready(function() {

            // Initialize Select2
            $('#department_id').select2();
            $('#product').select2();
            $('#product_type').select2();
      });

      // Get product by product Type
            $('#product_type').change(function(e) {
                e.preventDefault();
                var typeId = $(this).val();
                var url = location.origin + '/purchases/typed/' + typeId;
                if (typeId) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $('#product').empty();
                                $('#product').append('<option hidden value="0">Select Product</option>');
                                $.each(data, function(key, product) {
                                    $('select[name="product"]').append(
                                        '<option value="' + product.id +
                                        '" data-serialed="' + product.is_serial +
                                        '" data-waranted="' + product.is_license +
                                        '" >' +
                                        product.title +'</option>');
                                });
                            } else {
                                $('#product').empty();
                            }
                        }
                    });
                }
            });

</script>

@endpush
