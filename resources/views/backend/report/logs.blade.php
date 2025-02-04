@extends('layouts.backend.app')

@section('title', 'Reports | Users | Logs')

@push('css')
    <!-- JQuery Select Css -->
    <link href="{{ asset('backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('backend/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
        rel="stylesheet">
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td {
            vertical-align: middle !important;
        }

        .custom_width {
            width: 100px;
        }

        .custom_width2 {
            width: 140px;
        }

        li a span.text {
            padding-left: 30px !important;
        }

        .bs-searchbox input {
            padding-left: 20px !important;
        }

        .bootstrap-select .dropdown-toggle:focus {
            outline: 0 dotted #333333 !important;
            outline: 0 auto -webkit-focus-ring-color !important;
            outline-offset: 0 !important;

        }

        .form-group {
            margin-bottom: 20px !important;
        }

        .body {
            min-height: 110px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <select id="user" name="user" class="form-control show-tick" data-live-search="true">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-float">

                                <select name="action" id="action" class="form-control show-tick" data-live-search="true">
                                    <option value="">Actions </option>
                                    @foreach ($actions as $action)
                                        <option value="{{ $action->action }}">{{ $action->action }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" id="startDateFilter" class="form-control" placeholder="Start Date">
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date" id="endDateFilter" class="form-control" placeholder="End Date">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">
                <div class="header">
                    <h2>
                        User Logs
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="stockTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Details</th>
                                    <th>DateTime</th>
                                    <th>User Agent</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>


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

    <!-- Moment Plugin Js -->
    <script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>
    {{--
    <script src="{{ asset('backend/js/pages/tables/jquery-datatable.js') }}"></script> --}}
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            let exportOptions = {
                columns: ':visible', // Export only visible columns
                modifier: {
                    search: 'applied',
                    order: 'applied',
                    page: 'all' // Export all pages, not just the first one
                }
            };

            // Initialize DataTable
            let table = $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('reports.userlog.search') }}',
                    data: function (d) {
                        // Pass custom filter data to the server
                        d.user = $('#user').val();
                        d.action = $('#action').val();
                        d.start_date = $('#startDateFilter').val();
                        d.end_date = $('#endDateFilter').val();
                    }
                },
                columns: [
                    { data: 'user_name', name: 'users.name' },
                    { data: 'action', name: 'action' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'details', name: 'details' },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            if (data) {
                                let date = new Date(data);
                                return date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }
                            return '';
                        }
                     },
                     { data: 'user_agent', name: 'user_agent' },
                ],
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            modifier: { page: 'all' } // Exports all pages
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    },
                    {
                        extend: 'pdf',
                        orientation: 'landscape',
                        exportOptions: {
                            modifier: { page: 'all' }
                        },
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 11;
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';

                            doc.content[1].layout = {
                                tableWidth: '100%',
                            };
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            modifier: { page: 'all' }
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

            // Custom Filters Trigger Table Reload
            $('#user, #action, #startDateFilter, #endDateFilter').on('change', function () {
                table.ajax.reload();
            });

            $('#product_id').select2({
                width: '100%',
            });

            // Initialize Select2
            $('#type').select2();
            $('#status').select2();
            $('#store').select2();
            $('#supplier').select2();
            $('#condition').select2();
            $('#model').select2();

        });
    </script>
@endpush
