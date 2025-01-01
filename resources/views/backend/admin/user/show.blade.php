@extends('layouts.backend.app')

@section('title', 'User | Show')

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <a href="{{ route('users.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;">
                <i class="material-icons">keyboard_return</i>
                <span>Return</span>
            </a>
        </div>
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>User: {{ $user->name }} </h2>
                    </div>
                    <div class="body">
                        <div class="">
                            <h5>Roles</h5>
                            @if (!empty($user->roles))
                                @foreach ($user->roles as $v)
                                    <label class="label label-info">{{ $v->name }}</label>
                                @endforeach
                            @endif
                        </div>
                        <h5>Profile Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <td colspan="3">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee ID</th>
                                        <td>{{ sprintf('%03d', $user->profile->emply_id) }}</td>
                                        <th>Designation</th>
                                        <td>{{ $user->profile->designation }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td>{{ $user->profile->department->name }}</td>
                                        <th>Employee Status</th>
                                        <td>{!! $user->profile->status == 1
                                            ? '<span class=text-success>Active</span>'
                                            : '<span class=text-danger>Inactive</span>' !!} </td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $user->profile->phone }}</td>
                                        <th>Email</th>
                                        <td>{{ $user->email }} </td>
                                    </tr>
                                    <tr>
                                        <th>Date of Join</th>
                                        <td>{{ $user->profile->date_of_join }}</td>
                                        <th>Date of Resign</th>
                                        <td>{{ $user->profile->resign_date }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <button type="button" class="btn btn-danger waves-effect delete"
                                                data-delete-id="{{ $user->id }}" data-toggle="modal"
                                                data-target="#delete-modal">

                                                Delete User <i class="material-icons">delete</i>
                                            </button>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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
                        <h4 class="modal-title">Delete Department</h4>
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
    <script>
        $(".delete").click(function() {
            var data_id = $(this).data('delete-id');
            var url = location.origin + '/users/' + data_id;
            $('.delete_form').attr('action', url);

        });
    </script>
@endpush
