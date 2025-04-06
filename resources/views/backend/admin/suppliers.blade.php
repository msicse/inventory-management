@extends('layouts.backend.app')

@section('title', 'Suppliers')

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
        rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">

    <style>
        label {
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
                        <h2>
                            All Suppliers
                            <span class="badge ">{{ $suppliers->count() }}</span>
                        </h2>
                        @can('suppliers-create')
                            <button type="button" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;"
                                data-toggle="modal" data-target="#craeateModal">
                                <i class="material-icons">add</i>
                                <span>Add New Supplier</span>
                            </button>
                        @endcan
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($suppliers as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->company }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->address }}</td>
                                            <td>{{ $data->description }}</td>
                                            <td>
                                                @can('suppliers-edit')
                                                    <button type="button" class="btn btn-success waves-effect edit"
                                                        data-id="{{$data->id}}" data-toggle="modal" data-target="#editModal">
                                                        <i class="material-icons">create</i>
                                                    </button>

                                                @endcan
                                                @can('suppliers-delete')

                                                    <button type="button" class="btn btn-danger waves-effect delete"
                                                        data-delete-id="{{$data->id}}" data-toggle="modal"
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

    {{-- Modals --}}
    <!-- Create -->
    <div class="modal fade" id="craeateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('suppliers.store')}}" method="post" id="store_form">

                    <div class="modal-header custom-modal">
                        <h4 class="modal-title" id="defaultModalLabel">Add New Supplier</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group form-float">
                            <label class="form-label">Company Name</label>
                            <input type="text" id="addCompany" name="company" class="form-control" required>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label"> Contact Person</label>
                            <input type="text" name="name" id="addName" class="form-control" required>
                        </div>


                        <div class="form-group form-float">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" id="addPhone" class="form-control" required>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="addEmail" class="form-control" required>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="addAddress" rows="3" required></textarea>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" id="addDescription"
                                required></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect" id="store_form_submit">Save</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Edit  -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="edit-form" action="" method="post">
                    @csrf
                    @method('PUT')

                    <div class="modal-header custom-modal">
                        <h4 class="modal-title" id="defaultModalLabel">Edit Supplier</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group form-float">
                            <label class="form-label">Company Name</label>
                            <div class="form-line">
                                <input type="text" id="company" name="company" class="form-control" required>

                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label"> Name</label>
                            <div class="form-line">
                                <input type="text" id="name" name="name" class="form-control" required>

                            </div>
                        </div>


                        <div class="form-group form-float">
                            <label class="form-label">Phone</label>
                            <div class="form-line">
                                <input type="text" id="phone" name="phone" class="form-control" required>

                            </div>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Email</label>
                            <div class="form-line">
                                <input type="email" id="email" name="email" class="form-control" required>

                            </div>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Address</label>
                            <div class="form-line">
                                <textarea name="address" id="address" class="form-control" rows="3"></textarea>

                            </div>
                        </div>

                        <div class="form-group form-float">
                            <label class="form-label">Description</label>
                            <div class="form-line">
                                <textarea id="description" name="description" class="form-control" rows="3"></textarea>

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


    {{-- Delete Modal --}}
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <form class="delete_form" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header custom-modal">
                        <h4 class="modal-title">Delete Supplier</h4>
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


    <script>
        $('#test_btn').click(function () {
            toastr.info('Page Loaded!');
        });

        $('#store_form_submit').click(function (e) {

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('suppliers.store')}}",
                method: 'post',
                data: $('#store_form').serialize(),

                success: function (result) {
                    console.log(result);
                    if (result.errors) {
                        $.each(result.errors, function (key, value) {
                            toastr.error('Error:' + value);
                        });
                    }
                    else {

                        toastr.success('Success:' + result.success);
                        $('#craeateModal').modal('hide');
                        location.reload(true);

                    }
                }
            });
        });

        $(".edit").click(function (event) {
            var id = $(this).data('id');
            var update_url = location.origin + "/suppliers/" + id;
            var url = location.origin + '/suppliers/' + id;
            $('.edit-form').attr('action', update_url);

            $.get(url, function (data) {
                $('#company').val(data['company']);
                $('#name').val(data['name']);
                $('#phone').val(data['phone']);
                $('#email').val(data['email']);
                $("#address").text(data['address']);
                $("#description").text(data['description']);


                $('#type option[value="' + data['producttype_id'] + '"]').attr('selected', true);


            });
        });




        $(".delete").click(function () {
            var data_id = $(this).data('delete-id');
            var url = location.origin + '/suppliers/' + data_id;
            $('.delete_form').attr('action', url);

        });

        $("#store_form").validate();


    </script>


@endpush
