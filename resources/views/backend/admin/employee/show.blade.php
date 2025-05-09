@extends('layouts.backend.app')

@section('title', 'Admin | Employees | Show')

@push('css')
    <style>
        .show-image {
            margin-bottom: 20px;
        }

        .show-image img {
            height: 200px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Information of <strong>{{ $employee->name }}</strong>
                        </h2>
                        <div>
                            <a href="{{ route('employees.index') }}" class="btn btn-primary waves-effect">
                                <i class="material-icons">keyboard_return</i>
                                <span>Return</span>
                            </a>
                        </div>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td rowspan="3">
                                        <img src="{{ asset('images/employee/' . $employee->image) }}" height="80" alt="">
                                    </td>
                                    <th>Name</th>
                                    <td>{{ $employee->name }}</td>
                                    <th>Employee ID</th>
                                    <td>{{ sprintf('%03d', $employee->emply_id) }}</td>
                                <tr>

                                </tr>
                                <tr>
                                    <th>Designation</th>
                                    <td>{{ $employee->designation }}</td>
                                    <th>Department</th>
                                    <td>{{ $employee->department->name }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $employee->location }}</td>
                                    <th>Employee Status</th>
                                    <td>{!! $employee->status == 1
        ? '<span class=text-success>Active</span>'
        : '<span class=text-danger>Inactive</span>' !!} </td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $employee->phone }}</td>
                                    <th>Email</th>
                                    <td>{{ $employee->email }} </td>
                                </tr>
                                <tr>
                                    <th>Date of Join</th>
                                    <td>{{ formatDate($employee->date_of_join) }}</td>
                                    <th>Date of Resign</th>
                                    <td>{{ formatDate($employee->resign_date) }} </td>
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
                            Distribution History of <strong>{{ $employee->name }}</strong>

                        </h2>
                    </div>
                    <div class="body table-responsive">

                        <form method="post" action="{{ route('transections.multi.ack') }}">
                            @csrf
                            <input type="hidden" name="emply_id" value="{{ $employee->id }}" />


                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.L</th>
                                        <th>Product</th>
                                        <th>Product Model</th>
                                        <th>Product Status</th>
                                        <th>Asset Tag</th>
                                        <th>Serial No</th>
                                        <th>Issue Date</th>
                                        <th>Return Date</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->transections as $key => $data)
                                        <input type="hidden" name="issued_date" value="{{ $data->issued_date }}" />
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->stock->product->title }}</td>
                                            <td>{{ $data->stock->product->model }}</td>
                                            <td
                                                class="{{ $data->stock->asset_condition == 'good' ? 'text-success' : 'text-danger'  }} capitalize">
                                                {{ $data->stock->asset_condition }}
                                            </td>
                                            <td>{{ $data->stock->asset_tag}}</td>
                                            <td>{{ $data->stock->service_tag}}</td>
                                            <td>{{ formatDate($data->issued_date) }}</td>
                                            <td>{{ formatDate($data->return_date) }}</td>
                                            <td>{{ $data->quantity }}</td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="print_ack[]"
                                                        value="{{ $data->id }}" id="license-{{ $data->id }}">
                                                    <label class="form-check-label text-bold" for="license-{{ $data->id }}">
                                                    </label>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <button type="submit" class="btn btn-success">Print ACK</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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


    <script>
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    </script>
@endpush
