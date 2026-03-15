@extends('layouts.backend.app')

@section('title','Consumable Distribution')

@push('css')
    <link href="{{ asset('backend/js/pages/tables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td{ vertical-align: middle !important; }
        .stat-card { border-radius:8px; padding:18px; margin-bottom:15px; color:#fff; }
        .stat-num { font-size:28px; font-weight:700; }
        .stat-label { font-size:11px; text-transform:uppercase; font-weight:600; opacity:.9; }

        .panel-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(16, 29, 44, 0.45);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
        }

        .panel-backdrop.visible {
            opacity: 1;
            visibility: visible;
        }

        .slide-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: min(460px, 100%);
            height: 100vh;
            background: #fff;
            z-index: 1060;
            box-shadow: -10px 0 35px rgba(0, 0, 0, 0.18);
            transform: translateX(100%);
            transition: transform .28s ease;
            display: flex;
            flex-direction: column;
        }

        .slide-panel.visible {
            transform: translateX(0);
        }

        .panel-head {
            padding: 16px 18px;
            border-bottom: 1px solid #e7edf5;
            background: #f9fbff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-title {
            margin: 0;
            font-size: 18px;
            color: #1e3a5f;
            font-weight: 700;
        }

        .panel-body {
            padding: 16px 18px 90px;
            overflow-y: auto;
            flex: 1;
        }

        .panel-foot {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #e7edf5;
            padding: 12px 16px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .summary-chip {
            display: block;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f3f8ff;
            border: 1px solid #d7e1ef;
            color: #35536f;
            font-size: 12px;
            margin-bottom: 8px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><i class="material-icons" style="vertical-align: middle;">inventory_2</i> Consumable Distribution</h2>
                    @can('distribution-create')
                    <button type="button" id="openIssuePanel" class="btn btn-primary waves-effect pull-right" style="margin-bottom:10px;">
                        <i class="material-icons">add</i>
                        <span>Issue Consumable</span>
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="stat-card" style="background:#607d8b;"><div class="stat-num">{{ $stats['total_transactions'] }}</div><div class="stat-label">Total Issues</div></div></div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="stat-card" style="background:#2196f3;"><div class="stat-num">{{ $stats['active_assignments'] }}</div><div class="stat-label">Outstanding Issues</div></div></div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="stat-card" style="background:#4caf50;"><div class="stat-num">{{ $stats['returned_items'] }}</div><div class="stat-label">Fully Returned</div></div></div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="stat-card" style="background:#9c27b0;"><div class="stat-num">{{ $stats['unique_employees'] }}</div><div class="stat-label">Employees</div></div></div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="stat-card" style="background:#ff9800;"><div class="stat-num">{{ $stats['total_items_out'] }}</div><div class="stat-label">Issued Qty</div></div></div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Employee</label>
                            <select id="employee" class="form-control">
                                <option value="">All Employees</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} - {{ sprintf('%03d', $emp->emply_id) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Department</label>
                            <select id="department" class="form-control">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Product Type</label>
                            <select id="product_type" class="form-control">
                                <option value="">All Types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Status</label>
                            <select id="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Date From</label>
                            <input type="date" id="date_from" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Date To</label>
                            <input type="date" id="date_to" class="form-control">
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <button type="button" id="applyFilters" class="btn btn-primary waves-effect"><i class="material-icons">search</i> Apply</button>
                            <button type="button" id="clearFilters" class="btn btn-warning waves-effect"><i class="material-icons">clear</i> Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header"><h2>Consumable Distribution List</h2></div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="consumableTransectionsTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Issue Date</th>
                                    <th>Return Date</th>
                                    <th>Days</th>
                                    <th>Issued Qty</th>
                                    <th>Returned Qty</th>
                                    <th>Outstanding</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="returnPanelBackdrop" class="panel-backdrop"></div>
    <div id="returnSlidePanel" class="slide-panel" aria-hidden="true">
        <div class="panel-head">
            <h3 class="panel-title">Return Consumable</h3>
            <button type="button" class="btn btn-link" id="closeReturnPanel" style="text-decoration:none;">
                <i class="material-icons">close</i>
            </button>
        </div>
        <div class="panel-body">
            <span class="summary-chip"><strong>Employee:</strong> <span id="panelEmployee">-</span></span>
            <span class="summary-chip"><strong>Product:</strong> <span id="panelProduct">-</span></span>
            <span class="summary-chip"><strong>Outstanding:</strong> <span id="panelOutstanding">0</span></span>

            <div class="form-group" style="margin-top: 12px;">
                <label>Return Date</label>
                <input type="date" id="panelReturnDate" class="form-control">
            </div>

            <div class="form-group">
                <label>Return Quantity</label>
                <input type="number" min="1" id="panelReturnQty" class="form-control" placeholder="Enter quantity">
                <small class="text-muted">Quantity cannot exceed outstanding amount.</small>
                <div style="margin-top:8px;">
                    <button type="button" class="btn btn-default btn-sm waves-effect" id="fullReturnBtn">
                        <i class="material-icons" style="font-size:16px; vertical-align: middle;">done_all</i>
                        Full Return
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Optional Comment</label>
                <textarea id="panelReturnComment" class="form-control" rows="3" placeholder="Optional return note"></textarea>
            </div>
        </div>
        <div class="panel-foot">
            <button type="button" class="btn btn-default waves-effect" id="cancelReturnPanel">Cancel</button>
            <button type="button" class="btn btn-success waves-effect" id="submitReturnPanel">
                <i class="material-icons" style="vertical-align: middle;">check</i>
                Confirm Return
            </button>
        </div>
    </div>

    <div id="issuePanelBackdrop" class="panel-backdrop"></div>
    <div id="issueSlidePanel" class="slide-panel" aria-hidden="true">
        <div class="panel-head">
            <h3 class="panel-title">Issue Consumable</h3>
            <button type="button" class="btn btn-link" id="closeIssuePanel" style="text-decoration:none;">
                <i class="material-icons">close</i>
            </button>
        </div>

        <form action="{{ route('consumable.transections.store') }}" method="post" id="issueConsumableForm">
            @csrf
            <input type="hidden" name="date_of_issue" value="{{ now()->format('d-m-Y') }}">
            <input type="hidden" id="issue_remain">

            <div class="panel-body">
                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type" id="issue_product_type" class="form-control" required>
                        <option value="">Select product type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Product</label>
                    <select name="product" id="issue_product" class="form-control" required>
                        <option value="">Select product</option>
                    </select>
                    <small class="text-muted" id="issueStocksInfo"></small>
                </div>

                <div class="form-group">
                    <label>Employee</label>
                    <select name="employee" id="issue_employee" class="form-control" required>
                        <option value="">Select employee</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} - {{ sprintf('%03d', $emp->emply_id) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Issue Quantity</label>
                    <input type="number" min="1" name="quantity" id="issue_quantity" class="form-control" value="1" required>
                    <small class="text-muted" id="issueQtyHint">Quantity will be checked against available stock.</small>
                </div>

                <div class="form-group">
                    <label>Comment (optional)</label>
                    <textarea name="comment" id="issue_comment" class="form-control" rows="3" placeholder="Optional issue note"></textarea>
                </div>
            </div>

            <div class="panel-foot">
                <button type="button" class="btn btn-default waves-effect" id="cancelIssuePanel">Cancel</button>
                <button type="submit" class="btn btn-primary waves-effect">
                    <i class="material-icons" style="vertical-align: middle;">check</i>
                    Confirm Issue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/select2/select2.min.js') }}"></script>

<script>
$(document).ready(function() {
    let currentReturnId = null;

    function openIssuePanel() {
        $('#issuePanelBackdrop').addClass('visible');
        $('#issueSlidePanel').addClass('visible').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
    }

    function closeIssuePanel() {
        $('#issuePanelBackdrop').removeClass('visible');
        $('#issueSlidePanel').removeClass('visible').attr('aria-hidden', 'true');
        $('body').css('overflow', '');
        $('#issueConsumableForm')[0].reset();
        $('#issue_product').empty().append('<option value="">Select product</option>').trigger('change');
        $('#issueStocksInfo').text('');
        $('#issueQtyHint').text('Quantity will be checked against available stock.');
        $('#issue_remain').val('');
    }

    function openReturnPanel() {
        $('#returnPanelBackdrop').addClass('visible');
        $('#returnSlidePanel').addClass('visible').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
    }

    function closeReturnPanel() {
        $('#returnPanelBackdrop').removeClass('visible');
        $('#returnSlidePanel').removeClass('visible').attr('aria-hidden', 'true');
        $('body').css('overflow', '');
        currentReturnId = null;
        $('#panelEmployee, #panelProduct').text('-');
        $('#panelOutstanding').text('0');
        $('#panelReturnDate').val('');
        $('#panelReturnQty').val('');
        $('#panelReturnComment').val('');
    }

    $('#employee, #department, #product_type, #status').select2({ width: '100%' });
    $('#issue_product_type, #issue_product, #issue_employee').select2({ width: '100%', dropdownParent: $('#issueSlidePanel') });

    let table = $('#consumableTransectionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('consumable.transections.index') }}',
            data: function (d) {
                d.employee_id = $('#employee').val();
                d.department_id = $('#department').val();
                d.product_type = $('#product_type').val();
                d.status = $('#status').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'product_name', name: 'products.title' },
            { data: 'product_type_name', name: 'producttypes.name' },
            { data: 'employee_info', name: 'employees.name' },
            { data: 'department_name', name: 'departments.name' },
            { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
            { data: 'issued_date', name: 'cm_issue.movement_date' },
            { data: 'return_date', name: 'return_date', orderable: false, searchable: false },
            { data: 'days_with_asset', name: 'days_with_asset', searchable: false },
            { data: 'qty', name: 'cm_issue.qty' },
            { data: 'returned_qty', name: 'returned_qty', searchable: false },
            { data: 'outstanding_qty', name: 'outstanding_qty', searchable: false, orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        dom: 'Blfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        responsive: true
    });

    $('#applyFilters').on('click', function(){ table.ajax.reload(); });
    $('#clearFilters').on('click', function(){
        $('#employee, #department, #product_type, #status').val('').trigger('change');
        $('#date_from, #date_to').val('');
        table.ajax.reload();
    });

    $('#openIssuePanel').on('click', function () {
        openIssuePanel();
    });

    $('#closeIssuePanel, #cancelIssuePanel, #issuePanelBackdrop').on('click', function () {
        closeIssuePanel();
    });

    $('#issue_product_type').on('change', function() {
        let typeId = $(this).val();
        let url = '{{ url('typed-consumable-products') }}/' + typeId;

        $('#issue_product').empty().append('<option value="">Loading...</option>').trigger('change');
        $('#issueStocksInfo').text('');
        $('#issue_remain').val('');

        if (!typeId) {
            $('#issue_product').empty().append('<option value="">Select product</option>').trigger('change');
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#issue_product').empty().append('<option value="">Select product</option>');

                if (data && data.products && data.products.length) {
                    $.each(data.products, function(key, item) {
                        let label = item.title + ' - ' + item.brand + ' - ' + item.model + ' (Qty: ' + (item.quantity || 0) + ')';
                        $('#issue_product').append('<option value="' + item.id + '" data-available="' + (item.quantity || 0) + '">' + label + '</option>');
                    });
                } else {
                    $('#issue_product').append('<option value="">No stock available</option>');
                }

                $('#issue_product').trigger('change');
            },
            error: function() {
                $('#issue_product').empty().append('<option value="">Failed to load products</option>').trigger('change');
            }
        });
    });

    $('#issue_product').on('change', function() {
        let selected = $('#issue_product option:selected');
        let available = parseInt(selected.data('available') || 0, 10);

        if (!isNaN(available) && available >= 0) {
            $('#issue_remain').val(available);
            $('#issueStocksInfo').text('Available quantity: ' + available);
            $('#issueQtyHint').text('You can issue up to ' + available + ' units.');
            if (parseInt($('#issue_quantity').val() || 0, 10) > available) {
                $('#issue_quantity').val(available > 0 ? available : 1);
            }
        } else {
            $('#issue_remain').val('');
            $('#issueStocksInfo').text('');
            $('#issueQtyHint').text('Quantity will be checked against available stock.');
        }
    });

    $('#issue_quantity').on('input', function() {
        let qty = parseInt($(this).val() || 0, 10);
        let available = parseInt($('#issue_remain').val() || 0, 10);

        if (!isNaN(available) && available > 0 && qty > available) {
            $(this).val(available);
            toastr.warning('Issue quantity cannot exceed available stock');
        }
    });

    $('#issueConsumableForm').on('submit', function(e) {
        let qty = parseInt($('#issue_quantity').val() || 0, 10);
        let available = parseInt($('#issue_remain').val() || 0, 10);

        if (!$('#issue_product_type').val() || !$('#issue_product').val() || !$('#issue_employee').val()) {
            e.preventDefault();
            toastr.warning('Please complete all required fields');
            return;
        }

        if (isNaN(qty) || qty <= 0) {
            e.preventDefault();
            toastr.warning('Please enter a valid quantity');
            return;
        }

        if (!isNaN(available) && available > 0 && qty > available) {
            e.preventDefault();
            toastr.warning('Issue quantity cannot exceed available stock');
        }
    });

    $(document).on('click', '.open-return-panel', function () {
        currentReturnId = $(this).data('id');
        let employee = $(this).data('employee') || '-';
        let product = $(this).data('product') || '-';
        let outstanding = parseInt($(this).data('outstanding') || 0, 10);

        $('#panelEmployee').text(employee);
        $('#panelProduct').text(product);
        $('#panelOutstanding').text(outstanding);

        const today = new Date().toISOString().split('T')[0];
        $('#panelReturnDate').val(today);
        $('#panelReturnQty').val(outstanding > 0 ? outstanding : '');

        openReturnPanel();
    });

    $('#fullReturnBtn').on('click', function () {
        let outstanding = parseInt($('#panelOutstanding').text() || 0, 10);
        if (outstanding > 0) {
            $('#panelReturnQty').val(outstanding);
        }
    });

    $('#closeReturnPanel, #cancelReturnPanel, #returnPanelBackdrop').on('click', function() {
        closeReturnPanel();
    });

    $('#submitReturnPanel').on('click', function () {
        let returnDate = $('#panelReturnDate').val();
        let returnQty = $('#panelReturnQty').val();
        let outstanding = parseInt($('#panelOutstanding').text() || 0, 10);
        let comment = $('#panelReturnComment').val();

        if (!currentReturnId) {
            toastr.warning('No transaction selected');
            return;
        }

        if (!returnDate) {
            toastr.warning('Please select return date');
            return;
        }

        if (!returnQty || parseInt(returnQty, 10) <= 0) {
            toastr.warning('Please enter valid return quantity');
            return;
        }

        if (parseInt(returnQty, 10) > outstanding) {
            toastr.warning('Return quantity cannot exceed outstanding quantity');
            return;
        }

        $.post('{{ route('consumable.transections.mark.returned', ['id' => '___ID___']) }}'.replace('___ID___', currentReturnId), {
            _token: '{{ csrf_token() }}',
            return_date: returnDate,
            return_quantity: returnQty,
            comment: comment
        }).done(function(response) {
            if (response.success) {
                toastr.success(response.message || 'Return recorded successfully');
                closeReturnPanel();
                table.ajax.reload(null, false);
            } else {
                toastr.error(response.message || 'Failed to record return');
            }
        }).fail(function(xhr) {
            let msg = 'Error processing return';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            toastr.error(msg);
        });
    });
});
</script>
@endpush
