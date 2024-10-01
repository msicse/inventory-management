@extends('layouts.backend.app')

@section('title','Author | Employees | Show')

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
    <div class="block-header">
        <a href="{{ route('author.employees.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
            <i class="material-icons">keyboard_return</i>
            <span>Return</span>
        </a>
    
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Information of <strong>{{ $employee->name }}</strong>
                        
                    </h2>
                </div>
                <div class="body table-responsive">
                    <div class="show-image text-center">
                        <img src="{{ asset('images/employee/'.$employee->image) }}"  alt="" >
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <td>{{ $employee->name }}</td>
                                <th>Designation</th>
                                <td>{{ $employee->designation }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $employee->department->name }}</td>
                                <th>Employee ID</th>
                                <td> {{ $employee->emply_id }} </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $employee->phone }}</td>
                                <th>Email</th>
                                <td>{{ $employee->email }} </td>
                            </tr>
                            <tr>
                                <th>Date of Join</th>
                                <td>{{ $employee->date_of_join }}</td>
                                <th>Date of Resign</th>
                                <td>{{ $employee->resign_date }} </td>
                            </tr>
                            <tr>
                                <th>Employee Status</th>
                                <td>{!! $employee->status == 1 ? "<span class=text-success>Active</span>" : "<span class=text-danger>Inactive</span>" !!} </td>
                                <th>About</th>
                                <td>{{ $employee->about }}</td>
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
                        Transection History of <strong>{{ $employee->name }}</strong>
                        
                    </h2>
                </div>
                <div class="body table-responsive">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Product</th>
                                <th>Product Model</th>
                                <th>Serial No</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->transections as $key => $data )
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->stock->product->title }}</td>
                                <td>{{ $data->stock->product->model }}</td>
                                <td>{{ empty($data->stock->serial_no) ? "" : "RSC-". $data->stock->serial_no}}</td>
                                <td>{{ $data->issued_date}}</td>
                                <td>{{ $data->return_date}}</td>
                                <td>{{ $data->quantity}}</td>
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
<!-- Moment Plugin Js -->
<script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
<script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>


<script>


$('.datepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

</script>

@endpush
