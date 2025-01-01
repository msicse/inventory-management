@extends('layouts.backend.app')

@section('title','Admin | Employees')

@push('css')
    <!-- JQuery DataTable Css -->
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">
   <style>
        .table td{
            vertical-align: middle !important;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">add</i>
            <span>Add New Employee</span>
        </a>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Add New Purchases
                        <button id="addRow" class="btn btn-info">Add Row</button>
                        
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <label class="form-label">Select Department</label>
                                <select name="department" id="department" class="form-control show-tick">
                                    <option value="">Select Department</option>
                                    @foreach( $products as $data)
                                    <option value="{{ $data->id }}" {{ $data->id == old('department') ? 'selected' : '' }}>{{ $data->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                             
                        </div>  
                        <div class="col-md-10 remove-padding">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="purchTable">
                                    <tr>
                                        <th>#</th>
                                        <th>product_code</th>
                                        <th>name</th>
                                        <th>quantity</th>
                                        <th>unit_price</th>
                                        <th>total</th>
                                        <th>Option<th>
                                            
                                    </tr>
                            </table>

                        </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection

@push('js')


<script>

    $('#addRow').click(function(){
       // alert('ok');
        $('#purchTable').append("<tr><td>adcghavdhv</td>ok</tr>");

    });
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

</script>


@endpush
