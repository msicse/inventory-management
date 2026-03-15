@extends('layouts.backend.app')

@section('title','Product Type')

@push('css')
<!-- JQuery DataTable Css -->
<link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet">

@endpush
@section('content')
<div class="container-fluid">

    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Product Type
                        <span class="badge ">{{ $types->count() }}</span>
                    </h2>
                    <button type="button" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" data-toggle="modal" data-target="#craeateCategory">
                        <i class="material-icons">add</i>
                        <span>Add Product Type</span>
                    </button>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Prefix</th>
                                    <th>Parent</th>
                                    <th>Product Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Prefix</th>
                                    <th>Parent</th>
                                    <th>Product Count</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $types as $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        @if(($data->asset_class ?? 'FIXED') === 'CONSUMABLE')
                                            <span class="badge" style="background:#ff9800; color:white;">Consumable</span>
                                        @else
                                            <span class="badge" style="background:#607d8b; color:white;">Fixed</span>
                                        @endif
                                    </td>
                                    <td>{{ $data->prefix ?? '-' }}</td>
                                    <td>{{ optional($data->parent)->name ?? 'Root' }}</td>
                                    <td>{{ $data->stocks_count }}</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-success waves-effect " data-toggle="modal" data-target="#">
                                            <i class="material-icons">visibility</i>
                                        </button> --}}

                                        <button type="button" class="btn btn-warning waves-effect edit" data-id="{{$data->id}}"  title="Edit Product Type" data-toggle="modal" data-target="#editModal">
                                            <i class="material-icons">create</i>
                                        </button>

                                        <button type="button" class="btn btn-danger waves-effect delete" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal">

                                            <i class="material-icons">delete</i>
                                        </button>


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
<!-- Create Product Type -->
<div class="modal fade" id="craeateCategory" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('product-types.store')}}" method="post" id="store_form" enctype="multipart/form-data">
                <div class="modal-header custom-modal">
                    <h4 class="modal-title" id="defaultModalLabel">Add New Prodect Type</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                        <label id="name-error" class="error" for="product"></label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asset Class</label>
                        <select name="asset_class" class="form-control" required>
                            <option value="FIXED">Fixed</option>
                            <option value="CONSUMABLE">Consumable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prefix (optional)</label>
                        <input type="text" name="prefix" class="form-control" maxlength="4" placeholder="e.g. LP">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Parent Type</label>
                        <select name="parent_id" class="form-control parent-select">
                            <option value="">None (Root)</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
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


<!-- Edit Product Type -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" id="update_form" >
                <div class="modal-header custom-modal">
                    <h4 class="modal-title" id="defaultModalLabel">Edit Prodect Type</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                        <label id="name-error" class="error" for="product"></label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asset Class</label>
                        <select id="edit_asset_class" name="asset_class" class="form-control" required>
                            <option value="FIXED">Fixed</option>
                            <option value="CONSUMABLE">Consumable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prefix (optional)</label>
                        <input type="text" id="edit_prefix" name="prefix" class="form-control" maxlength="4" placeholder="e.g. LP">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Parent Type</label>
                        <select id="edit_parent_id" name="parent_id" class="form-control parent-select">
                            <option value="">None (Root)</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
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



{{-- Delete Modal --}}
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog">
        <form class="delete_form" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Role</h4>
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
<script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('backend/select2/select2.min.js') }}"></script>


<script>
    $('.parent-select').select2({
        width: '100%',
        placeholder: 'Select Parent Type',
        allowClear: true
    });

    $("#store_form").validate();
    $(".delete").click(function() {
        var data_id = $(this).data('delete-id');
        var url = location.origin + '/product-types/' + data_id;
        $('.delete_form').attr('action', url);
    });

    $(".edit").click(function(event) {
        var id = $(this).data('id');
        var url = location.origin + '/product-types/' + id;
        $('#update_form').attr('action', url);

        $.get(url, function(data) {
            $('#edit_name').val(data['name']);
            $('#edit_asset_class').val(data['asset_class'] || 'FIXED');
            $('#edit_prefix').val(data['prefix'] || '');
            $('#edit_parent_id').val(data['parent_id'] ? data['parent_id'].toString() : '');
            $('#edit_parent_id').trigger('change');
            $('#edit_asset_class').trigger('change');

            $('#edit_parent_id option').prop('disabled', false);
            $('#edit_parent_id option[value="' + id + '"]').prop('disabled', true);
        });

    });

</script>


@endpush
