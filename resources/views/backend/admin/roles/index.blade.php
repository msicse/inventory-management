@extends('layouts.backend.app')

@section('title', 'Roles')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
        rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .table td {
            vertical-align: middle !important;
        }

        /* Statistics Cards */
        .stat-card {
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .stat-card .icon {
            font-size: 48px;
            opacity: 0.9;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
            margin: 10px 0 5px 0;
        }

        .stat-card .label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            opacity: 0.9;
            line-height: 1.2;
        }

        .badge-permission {
            background: #667eea;
            color: white;
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-users {
            background: #4CAF50;
            color: white;
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

         <!-- Page Title & Action Button -->
        <div class="row clearfix">
            <div class="col-lg-12">

                <div class="card">
                    <div class="header">
                        <h2 class="text-uppercase">
                            <i class="material-icons" style="vertical-align: middle;">admin_panel_settings</i>
                           Role MANAGEMENT
                            <span class="badge "></span>
                        </h2>
                        <div>
                            @can("employee-create")
                                <a href="{{ route('roles.create') }}" class="btn btn-primary waves-effect pull-right"
                                    style="margin-bottom:10px;">
                                    <i class="material-icons">add</i>
                                    <span>Create New Role </span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="material-icons icon">admin_panel_settings</i>
                    <div class="number">{{ $roles->count() }}</div>
                    <div class="label">Total Roles</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                    <i class="material-icons icon">people</i>
                    <div class="number">{{ $roles->sum(fn($role) => $role->users->count()) }}</div>
                    <div class="label">Total Users</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #2196F3 0%, #00BCD4 100%); color: white;">
                    <i class="material-icons icon">verified_user</i>
                    <div class="number">{{ \Spatie\Permission\Models\Permission::count() }}</div>
                    <div class="label">Total Permissions</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #FF9800 0%, #FF5722 100%); color: white;">
                    <i class="material-icons icon">settings</i>
                    <div class="number">{{ $roles->max(fn($role) => $role->permissions->count()) }}</div>
                    <div class="label">Max Permissions</div>
                </div>
            </div>
        </div>

        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            All Roles
                            <small>Manage system roles and permissions</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Users</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Users</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($roles as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><strong>{{ $data->name }}</strong></td>
                                            <td>
                                                <span class="badge-permission">
                                                    <i class="material-icons" style="font-size: 12px; vertical-align: middle;">verified_user</i>
                                                    {{ $data->permissions->count() }} permissions
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-users">
                                                    <i class="material-icons" style="font-size: 12px; vertical-align: middle;">people</i>
                                                    {{ $data->users->count() }} users
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('roles.show', $data->id) }}"
                                                    class="btn btn-info waves-effect ">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                                @can("role-edit")
                                                    <a href="{{ route('roles.edit', $data->id) }}"
                                                        class="btn btn-primary waves-effect edit">
                                                        <i class="material-icons">create</i>
                                                    </a>
                                                @endcan
                                                @can("role-delete")
                                                    <button type="button" class="btn btn-danger waves-effect delete"
                                                        data-delete-id="{{ $data->id }}" data-toggle="modal"
                                                        data-target="#delete-modal">

                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                @endcan


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <script>
        $(".delete").click(function () {
            var data_id = $(this).data('delete-id');
            var url = location.origin + '/roles/' + data_id;
            $('.delete_form').attr('action', url);

        });
    </script>
@endpush
