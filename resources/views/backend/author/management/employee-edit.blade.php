@extends('layouts.backend.app')

@section('title','Author | Employees | Show')

@push('css')

    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

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
        <a href="{{ route('author.management.employees') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" >
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
                                <th>Number of Products</th>
                                <td>
                                    {{ $employee->transections->count() }}
                                </td>
                            </tr>
                            <tr>
                                <th>Employee Status</th>
                                <td>{!! $employee->status == 1 ? "<span class=text-success>Active</span>" : "<span class=text-danger>Inactive</span>" !!} </td>
                                <th>About</th>
                                <td>{{ $employee->about }}</td>
                            </tr>
                            <tr>
                                <form action="{{ route('author.management.employees.update', $employee->id) }}" method="post">
                                    @csrf
                                    <th>Date Of Resign</th>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="date_of_resign" value="{{ empty(old('date_of_resign')) ? $employee->resign_date : old('date_of_resign') }}" class="datepicker form-control" placeholder="Please choose Resign date..." required>
                                            </div>
                                        </div>
                                    
                                    </td>
                                    <th>
                                        <input type="checkbox" class="filled-in" id="ig_checkbox" name="status" value="2"  {{ $employee->status == 2 ? 'checked' : '' }}>
                                        <label for="ig_checkbox">Disabled</label>
                                    </th>
                                    <td>
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </td>

                                </form>
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

                <form action="" method="POST" >
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="body table-responsive">
     
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.L</th>
                                    <th>Product</th>
                                    <th>Product Model</th>
                                    <th>Serial No</th>
                                    <th>Product Status</th>
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
                                    <td>
                                        @if($data->stock->product_status == 1)
                                        <span class="text-success">Active</span>
                                        @elseif($data->stock->product_status == 2) 
                                        <span class="text-warning">Poor</span>
                                        @else
                                        <span class="text-danger">Damage</span>
                                        @endif
                                        
                                    
                                    </td>
                                    <td>{{ $data->issued_date}}</td>
                                    <td>
                                       @if(empty($data->return_date))
                                       <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="date_of_return[]" value="{{ old('date_of_return') }}" class="datepicker form-control" placeholder="Please choose Return Date..." required>
                                            </div>
                                        </div>
                                        @else

                                        {{ $data->return_date }}
                                        @endif
                                    
                                    </td>
                                    <td>{{ $data->quantity}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
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
        format: 'DD-MM-YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

</script>

@endpush
