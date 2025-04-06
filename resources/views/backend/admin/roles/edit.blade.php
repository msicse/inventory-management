@extends('layouts.backend.app')

@section('title', 'Edit')

@push('css')
    <style type="text/css">
        .margin-right {
            margin-right: 10px !important;
        }

        .margin-bt-0 {
            margin-bottom: 0 !important;
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
                        <h2>Edit Role: {{ $role->name }} </h2>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;">
                            <i class="material-icons">keyboard_return</i>
                            <span>Return</span>
                        </a>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                    <h5>Role Name</h5>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary waves-effect">Update</button>

                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
                                    <h5>Permissions</h5>
                                    <div class="row">
                                        @foreach ($permission as $value)
                                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 form-check margin-bt-0">
                                                <input class="form-check-input " type="checkbox"
                                                    name="permission[{{ $value->id }}]" value="{{ $value->id }}"
                                                    id="permission[{{ $value->id }}]" {{ in_array($value->id, $rolePermissions) ? 'checked' : ''}}>
                                                <label class="form-check-label form-label margin-right"
                                                    for="permission[{{ $value->id }}]">
                                                    <strong>{{ $value->name }}</strong>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>




                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>
    <!-- Create Department -->
    <div class="modal fade" id="addModal" tabindex="-1" Department="dialog">
        <div class="modal-dialog" Department="document">
            <div class="modal-content">
                <form action="{{ route('roles.store') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-header custom-modal">
                        <h4 class="modal-title" id="defaultModalLabel">Add New Department</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group form-float">
                            <label class="form-label">Department Name</label>
                            <div class="form-line">
                                <input type="text" name="name" class="form-control" required>

                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Short Name</label>
                            <div class="form-line">
                                <input type="text" name="short_name" class="form-control" required>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect">Save</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" Department="dialog">
        <div class="modal-dialog" Department="document">
            <div class="modal-content">
                <form class="edit-form" method="post" enctype="multipart/form-data">
                    <div class="modal-header custom-modal">
                        <h4 class="modal-title" id="defaultModalLabel">Edit Department</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="form-group form-float">
                            <label class="form-label">Department Name</label>
                            <div class="form-line">
                                <input type="text" id="name" name="name" class="form-control" required>

                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Short Name</label>
                            <div class="form-line">
                                <input type="text" id="short_name" name="short_name" class="form-control" required>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect">Save Change</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </form>
            </div>
        </div>
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
        $(".edit").click(function(event) {
            var id = $(this).data('id');
            var update_url = location.origin + "/roles/" + id;
            var url = location.origin + '/roles/' + id;
            $('.edit-form').attr('action', update_url);
            $.get(url, function(data) {
                $('#name').val(data['name']);
                $('#short_name').val(data['short_name']);

            });
        });
        $(".delete").click(function() {
            var data_id = $(this).data('delete-id');
            var url = location.origin + '/admin/roles/' + data_id;
            $('.delete_form').attr('action', url);

        });
    </script>
@endpush
