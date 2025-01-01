@extends('layouts.backend.app')

@section('title','Admin | Roles')

@push('css')
	<!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">

@endpush
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <button type="button" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;" data-toggle="modal" data-target="#craeateCategory">
            <i class="material-icons">add</i>
            <span>Add New Role</span>
        </button>

    </div>
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Categories
                        <span class="badge ">{{ $roles->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Users Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Users</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach( $roles as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->slug }}</td>
                                    <td>{{ $data->users->count() }}</td>
                                    <td>
                                        <!-- <button type="button" class="btn btn-success waves-effect " data-toggle="modal" data-target="#">
                                            <i class="material-icons">visibility</i>
                                        </button> -->

                                        <button type="button" class="btn btn-danger waves-effect delete" data-delete-id="{{$data->id}}" data-toggle="modal" data-target="#delete-modal" >

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
<!-- Create Role -->
<div class="modal fade" id="craeateCategory" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('roles.store')}}" method="post" enctype="multipart/form-data">
                <div class="modal-header custom-modal">
                    <h4 class="modal-title" id="defaultModalLabel">Add New Role</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" required>
                            <label class="form-label">Role Name</label>
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

<!-- Edit Category -->
<div class="modal fade" id="EditCategory" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="edit-category-form" method="post" enctype="multipart/form-data">
                <div class="modal-header custom-modal">
                    <h4 class="modal-title" id="defaultModalLabel">Edit Category</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label class="">Category Name</label>
                            <input  type="text" id="edit_name" name="name" value="" class="form-control">

                        </div>
                    </div>
                    <div class="text-center">
                        <img id="edit_image" height="100" width="115">
                    </div>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="file"  name="image" class="form-control">
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

<script>

$( ".edit" ).click(function( event ) {
    var id = $(this).data('id');
    var update_url = location.origin + "/admin/categories/" + id;
    var url = location.origin + '/admin/categories/' + id + '/edit';
    $('.edit-category-form').attr('action', update_url);
    $.get(url, function (data) {
        $('#edit_name').val(data['name']);
        $('#edit_image').attr('src',location.origin + '/storage/category/' + data['image']);
    });
});
$( ".delete" ).click(function() {
    var data_id=$(this).data('delete-id');
    var url=location.origin+'/admin/roles/'+data_id;
    $('.delete_form').attr('action',url);

});

</script>


@endpush
