@extends('layouts.backend.app')

@section('title','Onboarding | Accknowledgement')

@push('css')

    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td{
            vertical-align: middle !important;
        }
        .custom_width {
            width: 100px;
        }
        .custom_width2 {
            width: 140px;
        }
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
        .body {
            min-height: 110px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>Reports</h2>

    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    
                    <form action="{{ route('onboardings.submit') }}" method="get">
                        
                        <div class="col-md-3">
                            <div class="form-group form-float">
                            
                                <select name="employee" id="employee"  class="form-control show-tick" data-live-search="true" required>
                                    <option value="">Select Employee</option>
                                    @foreach( $employees as $data)
                                    <option value="{{ $data->id }}"}}>{{ $data->name." - ". $data->emply_id ." - ". $data->department->short_name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <input type="submit" value="Submit" class="btn btn-success">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            <div class="card">
                <div class="header">
                    <h2>
                        Onboarding Accknowledgement
                       
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Products</th>
                                    <th>Manual</th>
                                    <th>Action</th>
                                
                                </tr>
                            
                            </thead>
                            <tfoot>
                                <th>SL</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Products</th>
                                <th>Manual</th>
                                <th>Action</th>
                            </tfoot>
                            <tbody>
                                
                                @if(empty($employee))
                                <tr>
                                    <td colspan="5" class="text-center font-weight-bold">No Data Found</td>
                                </tr>
                                @else
                                
                                <form action="{{ route('onboardings.print', $employee->id ) }}" method="get">
                                
                                <tr>
                                    <td>1</td>
                                    <td>{{ $employee->emply_id }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->designation }}</td>

                                    <td>
                                    
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="laptop" value="1" id="laptop">
                                            <label class="form-check-label form-label" for="laptop">
                                                <strong>Laptop</strong>
                                            </label>
                                        </div>
                                        
                                        
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mobile" value="1" id="mobile">
                                            <label class="form-check-label form-label" for="mobile">
                                                <strong>Mobile</strong>
                                            </label>
                                        </div>
                                        
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mouse" value="1" id="mouse">
                                            <label class="form-check-label form-label" for="mouse">
                                                <strong>Mouse</strong>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pendrive" value="1" id="pendrive">
                                            <label class="form-check-label form-label" for="pendrive">
                                                <strong>Pendrive</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="camera" value="1" id="camera">
                                            <label class="form-check-label form-label" for="camera">
                                                <strong>Camera</strong>
                                            </label>
                                        </div>
                                        
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="ups" value="1" id="ups">
                                            <label class="form-check-label form-label" for="ups">
                                                <strong>UPS</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="laptop_bag" value="1" id="laptop_bag">
                                            <label class="form-check-label form-label" for="laptop_bag">
                                                <strong>Laptop Bag</strong>
                                            </label>
                                        </div>
                                        
                                    
                                    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="manual" value="1" id="manual">
                                                <label class="form-check-label form-label" for="manual">
                                                    <strong>Yes</strong>
                                                </label>
                                        </div>
                                        
                                        <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="return" value="1" id="return">
                                                <label class="form-check-label form-label" for="return">
                                                    <strong>Return</strong>
                                                </label>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        
                                         <button  type="submit" title="Print Acknowledgement" class="btn btn-primary waves-effect "  style="margin-top: 5px;">
                                            <i class="material-icons">print</i>
                                        </button>
                                    </td>
                                    
                                    </form>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>


@endsection

@push('js')

    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>


<script>

$(document).ready(function(){
        $('#employee').select2();
    });

</script>


@endpush

