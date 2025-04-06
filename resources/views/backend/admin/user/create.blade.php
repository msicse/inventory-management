@extends('layouts.backend.app')

@section('title', 'Users | Create')

@push('css')
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2> Add User </h2>
                        <a href="{{ route('users.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;">
                            <i class="material-icons">keyboard_return</i>
                            <span>Return</span>
                        </a>
                    </div>
                    <div class="body">
                        <form id="creaet-form" action="{{ route('users.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-float">
                                        <label class="form-label">Name</label>
                                        <input type="text" value="{{ old('name') }}" id="title" name="name"
                                            class="form-control" placeholder="Enter Name" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <label class="form-label">Username</label>
                                                <input type="text" value="{{ old('username') }}" name="username"
                                                    class="form-control" placeholder="Enter Username" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <label class="form-label">Employee ID</label>
                                                <input type="text" value="{{ old('employee_id') }}" name="employee_id"
                                                    class="form-control" placeholder="Enter Employee ID" required>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <label class="form-label">Email</label>
                                        <input type="text" value="{{ old('email') }}" id="email" name="email"
                                            class="form-control" placeholder="Enter email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Select Role</label>
                                    <div class="form-group form-float">
                                        <select name="roles[]" class="form-control show-tick" id="roles" multiple
                                            required>
                                            @foreach ($roles as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <label id="role-error" class="error" for="roles"></label>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">Password</label>
                                        <input type="password" value="{{ old('password') }}" id="password" name="password"
                                            class="form-control" placeholder="Enter Password" required>
                                    </div>
                                    <div class="form-group form-float">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" value="{{ old('password') }}" id="password"
                                            name="confirm-password" class="form-control" placeholder="Enter Password"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="" value="Save" class="btn btn-primary btn-lg"
                                        style="padding: 12px 60px;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>


    {{-- Delete Modal --}}
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <form class="delete_form" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong>Are you sure to delete this information ?</strong>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </form>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@push('js')
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>

    <script>
        $('#roles').select2({
            width: '100%',
            allowClear: true
        });

        $("#creaet-form").validate();

    </script>
@endpush
