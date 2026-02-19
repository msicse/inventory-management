@extends('layouts.backend.app')

@section('title', 'Admin | Purchases | Add (Redesign)')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        /* Bulk serial tag styles */
        .bulk-tags { display:flex; gap:6px; flex-wrap:wrap; align-items:center; padding:6px; border:1px solid #ced4da; border-radius:4px; min-height:40px; }
        .row-bulk-container { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        /* Ensure tag input doesn't expand to full width when bootstrap's .form-control is present */
        .bulk-tag-input.form-control, .bulk-tag-input.form-control-sm { width:auto; display:inline-block; padding:6px; margin:0; }
        .bulk-tag { background:#e9ecef; color:#212529; padding:6px 8px; border-radius:999px; display:inline-flex; align-items:center; gap:8px; font-size:0.9rem; }
        .bulk-tag .remove { cursor:pointer; background:transparent; border:0; padding:0 4px; color:#6c757d; font-weight:600; }
        .bulk-tag-input { border:0; outline:0; min-width:120px; font-size:0.95rem; padding:6px; }
        .bulk-tags:focus-within { box-shadow:0 0 0 0.2rem rgba(0,123,255,.15); }
        .row-card {
            border: 1px solid #e0e0e0;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
        }

        .row-card h5 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1rem
        }

        .row-card .form-group {
            margin-bottom: 6px
        }

        .row-card .form-control-sm {
            padding: .25rem .5rem;
            height: calc(1.5em + .5rem);
        }

        .field-error {
            color: #d9534f;
            font-size: 0.9em;
            display: none
        }

        .summary-badge {
            font-size: 0.9em;
            background: #f5f5f5;
            padding: 6px 10px;
            border-radius: 12px
        }

        textarea.serials-area {
            min-height: 48px;
        }
        /* Ensure modal headers align title and close button vertically */
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header .close {
            margin: 0;
            padding: 0.5rem 0.75rem;
            line-height: 1;
            background: transparent;
            border: 0;
        }
        /* Force modal title to the left and close button to the far right */
        .modal-header .modal-title {
            margin: 0;
            flex: 0 1 auto;
            text-align: left;
        }

        .modal-header .close {
            margin-left: auto;
        }

        /* === Add Product Button (Bootstrap 3.3.6 compatible) === */
        #add_product_btn {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 6px 16px;
            border-radius: 4px;
            background-color: #1565c0;
            background-image: -webkit-linear-gradient(315deg, #1e88e5 0%, #1565c0 100%);
            background-image: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            border: none;
            color: #fff;
            -webkit-box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35);
            box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35);
            -webkit-transition: all 0.25s ease;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        #add_product_btn:hover,
        #add_product_btn:focus {
            -webkit-box-shadow: 0 4px 12px rgba(21, 101, 192, 0.45);
            box-shadow: 0 4px 12px rgba(21, 101, 192, 0.45);
            background-color: #0d47a1;
            background-image: -webkit-linear-gradient(315deg, #1565c0 0%, #0d47a1 100%);
            background-image: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            color: #fff;
        }
        #add_product_btn:active {
            -webkit-box-shadow: 0 2px 6px rgba(21, 101, 192, 0.3);
            box-shadow: 0 2px 6px rgba(21, 101, 192, 0.3);
        }
        #add_product_btn > i.material-icons {
            font-size: 15px !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            margin-right: 6px !important;
            position: relative !important;
            top: -1px !important;
        }
        /* Pulse animation on idle to draw attention */
        @-webkit-keyframes subtlePulse {
            0%, 100% { -webkit-box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35); box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35); }
            50% { -webkit-box-shadow: 0 3px 16px rgba(21, 101, 192, 0.55); box-shadow: 0 3px 16px rgba(21, 101, 192, 0.55); }
        }
        @keyframes subtlePulse {
            0%, 100% { -webkit-box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35); box-shadow: 0 3px 8px rgba(21, 101, 192, 0.35); }
            50% { -webkit-box-shadow: 0 3px 16px rgba(21, 101, 192, 0.55); box-shadow: 0 3px 16px rgba(21, 101, 192, 0.55); }
        }
        #add_product_btn.pulse-hint {
            -webkit-animation: subtlePulse 2s ease-in-out infinite;
            animation: subtlePulse 2s ease-in-out infinite;
        }
        /* Add-product button area */
        .add-product-action {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #dee2e6;
            text-align: right;
        }
        .add-product-action .hint-text {
            color: #999;
            font-size: 12px;
            margin-right: 10px;
        }
        /* Secondary action buttons — teal accent (BS3 compatible) */
        .btn-add-modal {
            display: inline-block !important;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 3px;
            white-space: nowrap;
            color: #fff !important;
            border: none !important;
            background-color: #17a2b8 !important;
            -webkit-box-shadow: 0 1px 3px rgba(23, 162, 184, 0.3);
            box-shadow: 0 1px 3px rgba(23, 162, 184, 0.3);
            -webkit-transition: background-color 0.2s;
            transition: background-color 0.2s;
            cursor: pointer;
            vertical-align: middle;
        }
        .btn-add-modal:hover,
        .btn-add-modal:focus {
            color: #fff !important;
            background-color: #117a8b !important;
            -webkit-box-shadow: 0 2px 6px rgba(17, 122, 139, 0.4);
            box-shadow: 0 2px 6px rgba(17, 122, 139, 0.4);
        }
        .btn-add-modal:active {
            background-color: #0e6674 !important;
        }
        .btn.btn-add-modal > i.material-icons {
            font-size: 14px !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            position: relative !important;
            top: -1px !important;
            margin-right: 3px !important;
        }
        /* Fix long select text overflow in table-cell layout */
        .select-btn-wrap {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .select-btn-wrap > select {
            display: table-cell;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .select-btn-wrap > .btn-cell {
            display: table-cell;
            width: 70px;
            white-space: nowrap;
            vertical-align: middle;
            padding-left: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Add Purchase </h2>
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary waves-effect pull-right"
                            style="margin-bottom:10px;">
                            <i class="material-icons">keyboard_return</i>
                            <span>Return</span>
                        </a>
                    </div>
                    <div class="body">
                        <div id="alerts">
                            @if ($errors->any())
                                @foreach ($errors->all() as $err)
                                    <div class="alert alert-danger alert-dismissible" style="margin-bottom:8px">{{ $err }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
                                @endforeach
                            @endif
                        </div>
                        <form action="{{ route('purchases.store') }}" method="post" enctype="multipart/form-data"
                            id="purchase_form_redesign">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row row-card">
                                        <h5>Purchase Info</h5>
                                        <div class="form-group">
                                            <label for="supplier">Supplier <span class="text-danger">*</span></label>
                                            <div class="select-btn-wrap">
                                                <select name="supplier" id="supplier" class="form-control"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    @foreach ($suppliers as $data)
                                                        <option value="{{ $data->id }}" {{ old('supplier') == $data->id ? 'selected' : '' }}>{{ $data->company }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="btn-cell"><button type="button" class="btn btn-sm btn-add-modal"
                                                    id="openAddSupplier"><i class="material-icons">add</i> New</button></span>
                                            </div>
                                            <div class="field-error" id="error-supplier">Please select a supplier</div>
                                        </div>
                                        <div class="field-error" id="modal-error-supplier" style="display:none"></div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_no">Invoice No <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="invoice_no" id="invoice_no"
                                                        class="form-control form-control-sm" required value="{{ old('invoice_no') }}">
                                                    <div class="field-error" id="error-invoice_no">Invoice no is
                                                        required</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="challan_no">Challan No</label>
                                                <input type="text" name="challan_no" id="challan_no"
                                                    class="form-control form-control-sm" value="{{ old('challan_no') }}">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="date_of_purchase">Invoice / Purchase Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="date_of_purchase" id="date_of_purchase"
                                                    class="datepicker form-control form-control-sm"
                                                    placeholder="YYYY-MM-DD" required value="{{ old('date_of_purchase') }}">
                                                <div class="field-error" id="error-date_of_purchase">Date of purchase is
                                                    invalid</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="received_date">Challan / Received Date</label>
                                                <input type="date" name="received_date" id="received_date"
                                                    class="datepicker form-control form-control-sm"
                                                    placeholder="YYYY-MM-DD" value="{{ old('received_date') }}">
                                                <div class="field-error" id="error-received_date">Received date is invalid
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row-card">
                                        <h5>Add Products <span class="summary-badge" id="lineCount">0 lines</span>
                                            <small style="margin-left:12px; font-weight:600;">
                                                <span id="scannerStatus" class="summary-badge" style="background:#fff3cd;color:#856404;border:1px solid #ffeeba;font-size:0.8rem">Scanner: idle</span>
                                            </small>
                                        </h5>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="product_type">Product Type</label>
                                                <div class="select-btn-wrap">
                                                    <select name="product_type" id="product_type" class="form-control">
                                                        <option value="">Select Product Type</option>
                                                        @foreach ($types as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="btn-cell"><button type="button" class="btn btn-sm btn-add-modal" id="openAddProductType"><i class="material-icons">add</i> New</button></span>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="product">Product</label>
                                                <div class="select-btn-wrap">
                                                    <select id="product" class="form-control">
                                                        <option value="">Select Product</option>
                                                    </select>
                                                    <span class="btn-cell"><button type="button" class="btn btn-sm btn-add-modal"
                                                        id="openAddProduct"><i class="material-icons">add</i> New</button></span>
                                                </div>
                                                <div class="field-error" id="error-product" style="display:none"></div>
                                            </div>
                                        </div>

                                        <div class="add-product-action">
                                            <span class="hint-text">Select type & product, then</span>
                                            <button type="button" id="add_product_btn"
                                                class="btn btn-primary btn-md pulse-hint">
                                                <i class="material-icons">add_circle</i>
                                                Add Product to List
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="ptable_redesign">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Unit Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                    <th>Warranty (Days)</th>
                                                    <th>Serials (paste or enter)</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="ptablebody_redesign"></tbody>
                                        </table>

                                        <div class="text-right" style="font-size:18px">
                                            <strong>Grand Total:</strong>
                                            <input type="number" name="grand_total" id="grand_total_redesign" value="0"
                                                readonly min="0" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center" style="margin-top:18px">
                                <button type="submit" id="form_submit_redesign" class="btn btn-success btn-lg"
                                    disabled>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    <!-- Add Product Type Modal -->
                    <div class="modal fade" id="addProductTypeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Product Type</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form id="addProductTypeForm">
                                        @csrf
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                            <div class="field-error field-type-name" style="display:none"></div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" id="saveProductTypeBtn" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Supplier</h5>
                    <button type="button" class="close display-block pull-right" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addSupplierForm">
                        @csrf
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="company" class="form-control" required>
                            <div class="field-error field-company" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="field-error field-name" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                            <div class="field-error field-phone" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                            <div class="field-error field-email" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required></textarea>
                            <div class="field-error field-address" style="display:none"></div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="saveSupplierBtn" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        @csrf
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control" required>
                            <div class="field-error field-brand" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control" required>
                            <div class="field-error field-model" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                            <div class="field-error field-type" style="display:none"></div>
                        </div>
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" name="unit" class="form-control" required>
                            <div class="field-error field-unit" style="display:none"></div>
                        </div>
                        <div class="row" style="padding: 20px 10px;">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="serial" id="product_serial" value="1">
                                    <label class="form-check-label" for="product_serial">Serial</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="license" id="product_license" value="1">
                                    <label class="form-check-label" for="product_license">License / Warranty </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="taggable" id="product_taggable" value="1">
                                    <label class="form-check-label" for="product_taggable">Taggable</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_consumable" id="product_consumable" value="1">
                                    <label class="form-check-label" for="product_consumable">Consumable</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                            <div class="field-error field-description" style="display:none"></div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="saveProductBtn" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bulk Serial Modal -->
    <div class="modal fade" id="bulkSerialModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Serials (Paste or Upload CSV)</h5>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body margin-0 padding-0">
                    <div class="form-group">
                        <label>Paste or upload serials (CSV or newline separated)</label>
                        <div id="bulkSerialTags" class="bulk-tags" tabindex="0"></div>
                        <input id="bulkSerialTagInput" type="text" class="bulk-tag-input form-control " style="border: 1px solid #ced4da ; margin-top: 8px; width: 100%; font-size: 14px;" placeholder="Type or paste serials and press Enter" />
                        <input id="bulkSerialFile" type="file" accept=".csv,text/csv" class="form-control-file" style="margin-top:8px" />
                        <textarea id="bulkSerialsInput" class="form-control" style="display:none"
                            placeholder="serial1,serial2 or one per line"></textarea>
                        <div class="field-error" id="bulk-serial-error" style="display:none"></div>
                    </div>
                    <div class="text-right">
                        <button type="button" id="bulkSerialSave" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backend/plugins/momentjs/moment.js') }}"></script>
    <script src="{{ asset('backend/select2/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let rowCounter = 0;

        function calculateTotals() {
            let total = 0;
            document.querySelectorAll("input[name='total[]']").forEach(function (el) {
                total += parseFloat(el.value) || 0;
            });
            document.getElementById('grand_total_redesign').value = total.toFixed(2);
        }

        // sanitize decimal input (unit price)
        function sanitizeDecimalInput(el) {
            if (!el) return;
            // allow digits and one dot; remove other chars
            let v = el.value.toString();
            // remove invalid chars except dot
            v = v.replace(/[^0-9.\-]/g, '');
            // keep only first dot
            const parts = v.split('.');
            if (parts.length > 2) v = parts.shift() + '.' + parts.join('');
            // allow single leading minus only if needed; then enforce min elsewhere
            // trim to max four decimals
            if (v.indexOf('.') !== -1) {
                const p = v.split('.');
                p[1] = p[1].slice(0, 4);
                v = p[0] + '.' + p[1];
            }
            // replace lone '-' with ''
            if (v === '-') v = '';
            if (el.value !== v) el.value = v;
        }

        // sanitize integer input (quantity, warranty)
        function sanitizeIntegerInput(el) {
            if (!el) return;
            let v = el.value.toString();
            // remove non-digits and non-leading minus
            v = v.replace(/[^0-9\-]/g, '');
            // remove any minus signs that are not leading
            v = v.replace(/(?!^)-/g, '');
            // remove leading zeros unless single zero
            if (/^0[0-9]+/.test(v)) v = v.replace(/^0+/, '') || '0';
            if (el.value !== v) el.value = v;
        }

        function updateLineCount() {
            let lines = document.querySelectorAll('#ptablebody_redesign tr').length;
            document.getElementById('lineCount').innerText = lines + ' lines';
        }

            // NOTE: row tag UI is rendered live; updateRowPreview is kept as a no-op
        function updateRowPreview(row) {
            return; // tags are inline now
        }

        function updateSubmitState() {
            let supplier = document.getElementById('supplier') ? document.getElementById('supplier').value.trim() : '';
            let invoice = document.getElementById('invoice_no') ? document.getElementById('invoice_no').value.trim() : '';
            let date = document.getElementById('date_of_purchase') ? document.getElementById('date_of_purchase').value.trim() : '';
            let rows = document.querySelectorAll('#ptablebody_redesign tr');

            let ok = supplier && invoice && date && rows.length > 0;
            let hasError = false;

            if (ok) {
                rows.forEach(function (r) {
                    let priceEl = r.querySelector('.unit_price');
                    let qtyEl = r.querySelector('.quantity');
                    let price = priceEl ? priceEl.value : 0;
                    let qty = qtyEl ? qtyEl.value : 0;
                    if (!price || !qty) ok = false;
                });
            }

            // perform per-row live serial-count validation
            rows.forEach(function (r) {
                const isSerial = r.getAttribute('data-is_serial') === '1';
                const qtyInput = r.querySelector('input[name="quantity[]"]');
                const qty = qtyInput ? parseInt(qtyInput.value) || 0 : 0;
                const tags = getRowTags(r);
                const serialCount = tags ? tags.length : 0;
                let rowErr = r.querySelector('.row-serial-error');
                if (!rowErr) {
                    rowErr = document.createElement('div');
                    rowErr.className = 'row-serial-error text-danger small';
                    const td = r.querySelector('td:nth-child(7)');
                    if (td) td.appendChild(rowErr); else r.appendChild(rowErr);
                }

                if (isSerial) {
                    if (serialCount !== qty) {
                        rowErr.textContent = 'Serial count must match quantity';
                        rowErr.style.display = 'block';
                        hasError = true;
                    } else {
                        rowErr.textContent = '';
                        rowErr.style.display = 'none';
                    }
                } else {
                    if (rowErr) { rowErr.textContent = ''; rowErr.style.display = 'none'; }
                }
            });

            const submitBtn = document.getElementById('form_submit_redesign');
            if (submitBtn) submitBtn.disabled = (!ok) || hasError;
        }

        function showAlert(message, type = 'danger', timeout = 5000, undoCallback = null) {
            // prefer toastr if available
            if (window.toastr) {
                // map bootstrap types to toastr methods
                const map = { 'danger': 'error', 'error': 'error', 'success': 'success', 'warning': 'warning', 'info': 'info' };
                const fn = map[type] || 'info';
                // show undo button by appending HTML (toastr supports HTML when escapeHtml=false in some configs)
                if (undoCallback) {
                    // show basic message and call undo when user clicks the toast
                    const $toast = toastr[fn](message, null, { timeOut: timeout, closeButton: true, tapToDismiss: false });
                    // attach click to call undo (best-effort)
                    if ($toast && $toast.find) {
                        $toast.find('.toast-message').on('click', function () { try { undoCallback(); } catch (e) {} });
                    }
                } else {
                    toastr.options = { "closeButton": true, "progressBar": true, "timeOut": timeout };
                    toastr[fn](message);
                }
                return;
            }

            // fallback to inline alerts
            const id = 'alert-' + Date.now();
            const container = document.getElementById('alerts');
            const div = document.createElement('div');
            div.className = `alert alert-${type} alert-dismissible`;
            div.id = id;
            div.style.marginBottom = '8px';
            div.innerHTML = `<span>${message}</span>`;
            if (undoCallback) {
                const undo = document.createElement('button');
                undo.type = 'button';
                undo.className = 'btn btn-link btn-sm';
                undo.style.marginLeft = '8px';
                undo.innerText = 'Undo';
                undo.addEventListener('click', function () { undoCallback(); div.remove(); });
                div.appendChild(undo);
            }
            const closeBtn = document.createElement('button');
            closeBtn.type = 'button'; closeBtn.className = 'close'; closeBtn.innerHTML = '&times;';
            closeBtn.addEventListener('click', () => div.remove());
            div.appendChild(closeBtn);
            if (container) container.prepend(div);
            if (timeout > 0) { setTimeout(() => { try { div.remove(); } catch (e) { } }, timeout); }
        }

        document.addEventListener('DOMContentLoaded', function () {
            $('#supplier').select2({ width: '100%' });
            $('#product_type').select2({ width: '100%' });
            $('#product').select2({ width: '100%' });

            try {
                if ($.fn && $.fn.bootstrapMaterialDatePicker) {
                    $('.datepicker').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD', clearButton: true, weekStart: 1, time: false });
                } else {
                    // fallback to native date input if plugin not available
                    $('.datepicker').each(function () { $(this).attr('type', 'date'); });
                }
            } catch (e) {
                $('.datepicker').each(function () { $(this).attr('type', 'date'); });
            }

            // listen for multiple change events (plugin may dispatch different events)
            $('#date_of_purchase').on('change blur input', function () {
                const val = $(this).val();
                if (val && !$('#received_date').val()) {
                    $('#received_date').val(val);
                }
                updateSubmitState();
            });

            // Debugging: log datepicker initialization status
            try {
                console.log('Datepicker plugin present:', !!($.fn && $.fn.bootstrapMaterialDatePicker));
                console.log('#date_of_purchase type:', $('#date_of_purchase').attr('type'));
                console.log('#received_date type:', $('#received_date').attr('type'));
            } catch (e) { console.warn('Datepicker debug failed', e); }

            // --- Scanner input support ---
            // hidden input to capture scanner keyboard input (most scanners send an Enter)
            (function() {
                const scannerInput = document.createElement('input');
                scannerInput.id = 'scannerInput';
                scannerInput.type = 'text';
                scannerInput.autocomplete = 'off';
                scannerInput.style.position = 'fixed';
                scannerInput.style.left = '-9999px';
                document.body.appendChild(scannerInput);

                let scannerTargetRow = null;
                const statusEl = document.getElementById('scannerStatus');

                function setScannerStatus(text, cls) {
                    if (!statusEl) return;
                    statusEl.textContent = 'Scanner: ' + text;
                    if (cls) { statusEl.style.background = cls.bg || statusEl.style.background; statusEl.style.color = cls.color || statusEl.style.color; }
                }

                function appendSerialToRow(row, serial) {
                    if (!row || !serial) return false;
                    serial = serial.trim();
                    if (!/^[A-Za-z0-9\-_.]+$/.test(serial)) {
                        if (window.toastr) toastr.error('Invalid serial: ' + serial);
                        return false;
                    }
                    const existing = getAllSerials();
                    // allow adding to this row only if not globally duplicated
                    if (existing.indexOf(serial) !== -1) {
                        if (window.toastr) toastr.warning('Duplicate serial: ' + serial);
                        return false;
                    }
                    const tagContainer = row.querySelector('.row-bulk-tags');
                    if (tagContainer) {
                        addRowTag(row, serial);
                        if (window.toastr) toastr.success('Scanned: ' + serial);
                        return true;
                    }
                    // fallback: hidden input
                    const productId = row.querySelector("input[name='product_id[]']").value;
                    const td = row.querySelector('td:nth-child(7)');
                    const inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = `serials-${productId}[]`; inp.value = serial;
                    td.appendChild(inp);
                    const qty = row.querySelector('.quantity');
                    if (qty) { qty.value = (parseInt(qty.value)||0) + 1; qty.dispatchEvent(new Event('input')); }
                    if (window.toastr) toastr.success('Scanned: ' + serial);
                    return true;
                }

                // focus scanner input when a serial textarea gains focus
                document.addEventListener('focusin', function(e){
                    const target = e.target;
                    const ta = (target && target.classList && (target.classList.contains('serials-area') || target.classList.contains('row-bulk-input'))) ? target : null;
                    if (ta) {
                        scannerTargetRow = ta.closest('tr');
                        scannerInput.focus();
                        setScannerStatus('ready', { bg: '#d4edda', color: '#155724' });
                    }
                });

                // focus scanner input only when clicking non-form areas so form controls remain typable
                document.addEventListener('click', function(e){
                    try {
                        // if click happened inside a modal, do not steal focus (allow typing/paste inside modal)
                        if (e.target.closest && e.target.closest('.modal')) return;

                        // if click landed on or inside any form control, do not move focus
                        if (e.target.matches && (e.target.matches('input, textarea, select, button') || e.target.closest && e.target.closest('input, textarea, select, button'))) {
                            return;
                        }

                        // if click is outside form controls, focus hidden scanner to capture scans
                        scannerInput.focus();
                        setScannerStatus('idle', { bg: '#fff3cd', color: '#856404' });
                    } catch (err) { }
                });

                scannerInput.addEventListener('keydown', function(e){
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const val = scannerInput.value.trim();
                        if (!val) { scannerInput.value = ''; return; }
                        if (!scannerTargetRow) { if (window.toastr) toastr.info('Select a serial field first'); scannerInput.value = ''; return; }
                        appendSerialToRow(scannerTargetRow, val);
                        scannerInput.value = '';
                        setScannerStatus('ready', { bg: '#d4edda', color: '#155724' });
                    }
                });

                scannerInput.addEventListener('paste', function(e){
                    setTimeout(() => {
                        const raw = scannerInput.value;
                        scannerInput.value = '';
                        if (!raw) return;
                        const items = raw.split(/[\n,\r]+/).map(s => s.trim()).filter(Boolean);
                        if (!scannerTargetRow) { if (window.toastr) toastr.info('Select a serial field first'); return; }
                        items.forEach(it => appendSerialToRow(scannerTargetRow, it));
                    }, 10);
                });

                // expose for debugging
                window._scannerHelper = { focus: () => scannerInput.focus(), setTargetRow: r => scannerTargetRow = r };
            })();
            // --- end scanner support ---

            $('#product_type').change(function () {
                var typeId = $(this).val();
                var url = location.origin + '/purchases/typed/' + typeId;
                if (typeId) {
                    $.getJSON(url, function (data) {
                        $('#product').empty().append('<option value="">Select Product</option>');
                        $.each(data, function (i, p) {
                            $('#product').append(`<option value="${p.id}" data-is_serial="${p.is_serial}" data-is_warranty="${p.is_license}">${p.title} - ${p.brand} - ${p.model}</option>`);
                        });
                    });
                }
            });

            document.getElementById('openAddSupplier').addEventListener('click', function () {
                $('#addSupplierModal').modal('show');
            });

            function validateSupplierForm() {
                let form = document.getElementById('addSupplierForm');
                let valid = true;
                $('#addSupplierForm .field-error').hide().text('');
                const company = form.querySelector('[name="company"]').value.trim();
                const name = form.querySelector('[name="name"]').value.trim();
                const phone = form.querySelector('[name="phone"]').value.trim();
                const email = form.querySelector('[name="email"]').value.trim();
                const address = form.querySelector('[name="address"]').value.trim();
                if (!company) { $('.field-company').show().text('Company is required'); valid = false; }
                if (!name) { $('.field-name').show().text('Contact name is required'); valid = false; }
                if (!phone) { $('.field-phone').show().text('Phone is required'); valid = false; }
                if (!address) { $('.field-address').show().text('Address is required'); valid = false; }
                if (email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { $('.field-email').show().text('Invalid email'); valid = false; }
                return valid;
            }

            document.getElementById('saveSupplierBtn').addEventListener('click', function () {
                if (!validateSupplierForm()) return;
                let form = document.getElementById('addSupplierForm');
                let data = $(form).serialize();
                let url = "{{ route('suppliers.store') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function (res) {
                        let opt = new Option(res.company, res.id, true, true);
                        $('#supplier').append(opt).trigger('change');
                        $('#addSupplierModal').modal('hide');
                        showAlert('Supplier added', 'success');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errs = xhr.responseJSON.errors;
                            Object.keys(errs).forEach(function (k) {
                                let el = $('#addSupplierForm').find(`[name="${k}"]`).siblings('.field-error');
                                if (el.length) { el.show().text(errs[k][0]); }
                            });
                        } else {
                            showAlert('Failed to create supplier', 'danger');
                        }
                    }
                });
            });

            document.getElementById('openAddProduct').addEventListener('click', function () {
                $('#addProductModal').modal('show');
            });

            document.getElementById('openAddProductType').addEventListener('click', function () {
                $('#addProductTypeModal').modal('show');
            });

            function validateProductTypeForm() {
                let form = document.getElementById('addProductTypeForm');
                let valid = true;
                $('#addProductTypeForm .field-error').hide().text('');
                const name = form.querySelector('[name="name"]').value.trim();
                if (!name) { $('.field-type-name').show().text('Name is required'); valid = false; }
                return valid;
            }

            document.getElementById('saveProductTypeBtn').addEventListener('click', function () {
                if (!validateProductTypeForm()) return;
                let form = document.getElementById('addProductTypeForm');
                let data = $(form).serialize();
                let url = "{{ route('product-types.store') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function (res) {
                        let text = res.name || ('Type ' + res.id);
                        // add option to main product_type select (select2) and product modal type select
                        let opt = new Option(text, res.id, true, true);
                        $('#product_type').append(opt).trigger('change');

                        // ensure product modal's type select also receives the new option
                        let opt2 = new Option(text, res.id, true, true);
                        let $prodTypeSelect = $('#addProductForm').find('select[name="type"]');
                        if ($prodTypeSelect.length) {
                            $prodTypeSelect.append(opt2);
                            // set it as selected so user sees it immediately
                            $prodTypeSelect.val(res.id);
                        }

                        $('#addProductTypeModal').modal('hide');
                        showAlert('Product type added', 'success');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errs = xhr.responseJSON.errors;
                            Object.keys(errs).forEach(function (k) {
                                let el = $('#addProductTypeForm').find(`[name="${k}"]`).siblings('.field-error');
                                if (el.length) { el.show().text(errs[k][0]); }
                            });
                        } else {
                            showAlert('Failed to create product type', 'danger');
                        }
                    }
                });
            });

            function validateProductForm() {
                let form = document.getElementById('addProductForm');
                let valid = true;
                $('#addProductForm .field-error').hide().text('');
                const brand = form.querySelector('[name="brand"]').value.trim();
                const model = form.querySelector('[name="model"]').value.trim();
                const type = form.querySelector('[name="type"]').value.trim();
                const unit = form.querySelector('[name="unit"]').value.trim();
                const desc = form.querySelector('[name="description"]').value.trim();
                if (!brand) { $('.field-brand').show().text('Brand is required'); valid = false; }
                if (!model) { $('.field-model').show().text('Model is required'); valid = false; }
                if (!type) { $('.field-type').show().text('Type is required'); valid = false; }
                if (!unit) { $('.field-unit').show().text('Unit is required'); valid = false; }
                if (!desc) { $('.field-description').show().text('Description is required'); valid = false; }
                return valid;
            }

            document.getElementById('saveProductBtn').addEventListener('click', function () {
                if (!validateProductForm()) return;
                let form = document.getElementById('addProductForm');
                let data = $(form).serialize();
                let url = "{{ route('products.store') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function (res) {
                        let text = res.title || ('Product ' + res.id);
                        let opt = new Option(text, res.id, true, true);
                        $(opt).attr('data-is_serial', res.is_serial == 1 ? 1 : 2);
                        $(opt).attr('data-is_warranty', res.is_license == 1 ? 1 : 2);
                        $('#product').append(opt).trigger('change');
                        $('#addProductModal').modal('hide');
                        showAlert('Product added', 'success');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errs = xhr.responseJSON.errors;
                            Object.keys(errs).forEach(function (k) {
                                let el = $('#addProductForm').find(`[name="${k}"]`).siblings('.field-error');
                                if (el.length) { el.show().text(errs[k][0]); }
                            });
                        } else {
                            showAlert('Failed to create product', 'danger');
                        }
                    }
                });
            });

            function getRowTags(row) {
                return Array.from((row.querySelectorAll('.row-bulk-tags .row-tag') || [])).map(t => t.dataset.value);
            }
            // expose helper globally to ensure handlers bound in different scopes can call it
            window.getRowTags = getRowTags;

            function getAllSerials() {
                let arr = [];
                document.querySelectorAll('#ptablebody_redesign tr').forEach(function (r) {
                    // prefer inline tags
                    const tags = getRowTags(r);
                    if (tags && tags.length) { arr = arr.concat(tags); return; }

                    let ta = r.querySelector('.serials-area');
                    if (ta) {
                        let items = ta.value.trim() === '' ? [] : ta.value.trim().split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                        arr = arr.concat(items);
                    }
                    r.querySelectorAll('input[type="hidden"]').forEach(function (h) {
                        if (h.name && h.name.startsWith('serials-')) arr.push(h.value);
                    });
                });
                return arr;
            }

            // Add a tag to a specific row (inline tag UI)
            function addRowTag(row, val) {
                val = (val || '').trim();
                if (!val) return;
                if (!/^[A-Za-z0-9\-_.]+$/.test(val)) { if (window.toastr) toastr.error('Invalid serial: ' + val); return; }
                // avoid duplicates within the row
                const existing = getRowTags(row);
                if (existing.indexOf(val) !== -1) return;
                // avoid duplicates globally
                let global = getAllSerials();
                // exclude this row's existing tags from global check
                global = global.filter(g => existing.indexOf(g) === -1);
                if (global.indexOf(val) !== -1) { if (window.toastr) toastr.warning('Duplicate serial: ' + val); return; }

                const tagContainer = row.querySelector('.row-bulk-tags');
                if (!tagContainer) return;
                const span = document.createElement('span');
                span.className = 'row-tag bulk-tag';
                span.dataset.value = val;
                span.innerHTML = `<span class="text">${val}</span><button type="button" class="remove" aria-label="Remove">×</button>`;
                span.querySelector('.remove').addEventListener('click', function () {
                    span.remove();
                    // update qty
                    const qtyInput = row.querySelector('.quantity');
                    if (qtyInput) { qtyInput.value = getRowTags(row).length; qtyInput.dispatchEvent(new Event('input')); }
                });
                tagContainer.appendChild(span);
                // update qty
                const qtyInput = row.querySelector('.quantity');
                if (qtyInput) { qtyInput.value = getRowTags(row).length; qtyInput.dispatchEvent(new Event('input')); }
            }

            function bindRowEvents(row) {
                let priceEl = row.querySelector('.unit_price');
                if (priceEl) {
                    priceEl.addEventListener('input', function () {
                        sanitizeDecimalInput(this);
                        let price = parseFloat(this.value) || 0;
                        if (price < 0) { this.value = Math.abs(price); price = parseFloat(this.value) || 0; }
                        let qty = parseInt(row.querySelector('.quantity').value) || 0;
                        row.querySelector("input[name='total[]']").value = (price * qty).toFixed(2);
                        calculateTotals();
                        updateSubmitState();
                    });
                }

                let qtyEl = row.querySelector('.quantity');
                if (qtyEl) {
                    qtyEl.addEventListener('input', function () {
                        sanitizeIntegerInput(this);
                        let qty = parseInt(this.value) || 0;
                        if (qty < 1) { this.value = Math.max(1, qty); qty = parseInt(this.value) || 0; }
                        let price = parseFloat(row.querySelector('.unit_price').value) || 0;
                        row.querySelector("input[name='total[]']").value = (price * qty).toFixed(2);
                        calculateTotals();
                        updateSubmitState();
                    });
                }

                // inline tag UI
                let tagContainer = row.querySelector('.row-bulk-tags');
                let tagInput = row.querySelector('.row-bulk-input');
                if (tagContainer && tagInput) {
                    // handle Enter to add tags
                    tagInput.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const v = this.value.trim();
                            if (!v) return;
                            const parts = v.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                            parts.forEach(p => addRowTag(row, p));
                            this.value = '';
                        }
                    });
                    // improved paste handler: use clipboardData when available for immediate parsing
                    tagInput.addEventListener('paste', function (e) {
                        try {
                            let raw = '';
                            if (e && e.clipboardData && e.clipboardData.getData) {
                                raw = e.clipboardData.getData('text');
                                e.preventDefault();
                            }
                            const process = (text) => {
                                const parts = text.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                                parts.forEach(p => addRowTag(row, p));
                            };
                            if (raw) {
                                process(raw);
                                this.value = '';
                            } else {
                                // fallback to previous behavior
                                setTimeout(() => {
                                    const raw2 = this.value;
                                    this.value = '';
                                    if (!raw2) return;
                                    process(raw2);
                                }, 10);
                            }
                        } catch (err) { /* ignore */ }
                    });

                    // add button (click) and blur handler to add any leftover value
                    const addBtn = row.querySelector('.row-add-serial-btn');
                    if (addBtn) {
                        addBtn.addEventListener('click', function () {
                            const v = tagInput.value.trim();
                            if (!v) return;
                            const parts = v.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                            parts.forEach(p => addRowTag(row, p));
                            tagInput.value = '';
                        });
                    }

                    tagInput.addEventListener('blur', function () {
                        setTimeout(() => {
                            const v = this.value.trim();
                            if (!v) return; // keep focus transitions safe
                            const parts = v.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                            parts.forEach(p => addRowTag(row, p));
                            this.value = '';
                        }, 150);
                    });
                }

                // warranty (if present) should accept only integers >= 0
                let warrantyEl = row.querySelector('.warranty');
                if (warrantyEl) {
                    warrantyEl.addEventListener('input', function () {
                        sanitizeIntegerInput(this);
                        if (this.value === '') return;
                        let v = parseInt(this.value) || 0;
                        if (v < 0) { this.value = 0; }
                    });
                }

                let bulkBtn = row.querySelector('.bulk-serial-btn');
                if (bulkBtn) {
                    bulkBtn.addEventListener('click', function () {
                        currentBulkRow = row;
                        let existing = '';
                        const tags = getRowTags(row);
                        if (tags && tags.length) existing = tags.join('\n');
                        else {
                            const ta = row.querySelector('.serials-area');
                            if (ta) existing = ta.value;
                            else {
                                const productId = row.querySelector("input[name='product_id[]']").value;
                                const hidden = Array.from(row.querySelectorAll(`input[name='serials-${productId}[]']`)).map(h => h.value);
                                if (hidden.length) existing = hidden.join('\n');
                            }
                        }
                        document.getElementById('bulkSerialsInput').value = existing;
                        document.getElementById('bulk-serial-error').style.display = 'none';
                        $('#bulkSerialModal').data('applied', false).modal('show');
                    });
                }

                // render preview initially
                updateRowPreview(row);

                let removeBtn = row.querySelector('.remove-row');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function () {
                        const removedHtml = row.outerHTML;
                        row.remove();
                        calculateTotals();
                        updateLineCount();
                        updateSubmitState();
                        showAlert('Row removed', 'warning', 8000, function () {
                            const tbody = document.getElementById('ptablebody_redesign');
                            tbody.insertAdjacentHTML('beforeend', removedHtml);
                            const newRow = tbody.lastElementChild;
                            bindRowEvents(newRow);
                        });
                    });
                }
            }

            var currentBulkRow = null;

            document.getElementById('bulkSerialSave').addEventListener('click', function () {
                // collect tags from modal tag UI
                let items = [];
                document.querySelectorAll('#bulkSerialTags .bulk-tag').forEach(function (t) { items.push(t.dataset.value); });
                const errorEl = document.getElementById('bulk-serial-error');
                errorEl.style.display = 'none';

                if (!currentBulkRow) { errorEl.style.display = 'block'; errorEl.textContent = 'No target row'; return; }

                const invalid = items.find(i => !/^[A-Za-z0-9\-_.]+$/.test(i));
                if (invalid) { errorEl.style.display = 'block'; errorEl.textContent = 'Invalid serial: ' + invalid; return; }

                let existing = getAllSerials();
                // exclude this row's existing tags from global conflict check
                let curItems = getRowTags(currentBulkRow);
                if (curItems && curItems.length) {
                    existing = existing.filter(e => curItems.indexOf(e) === -1);
                }

                const dup = items.find(i => existing.indexOf(i) !== -1);
                if (dup) { errorEl.style.display = 'block'; errorEl.textContent = 'Duplicate serial: ' + dup; return; }

                // apply tags to row: clear existing and add new
                const tagContainer = currentBulkRow.querySelector('.row-bulk-tags');
                if (tagContainer) {
                    Array.from(tagContainer.querySelectorAll('.row-tag')).forEach(function (n) { n.remove(); });
                    items.forEach(function (s) { addRowTag(currentBulkRow, s); });
                } else {
                    // fallback to textarea
                    let curTa = currentBulkRow.querySelector('.serials-area');
                    if (curTa) curTa.value = items.join('\n');
                }

                let qtyInput = currentBulkRow.querySelector('.quantity');
                if (qtyInput) { qtyInput.value = items.length; qtyInput.dispatchEvent(new Event('input')); }
                $('#bulkSerialModal').data('applied', true);
                $('#bulkSerialModal').modal('hide');
                showAlert('Serials applied', 'success');
            });

            // Tag UI helpers for bulk modal
            function addBulkTag(val) {
                val = (val || '').trim();
                if (!val) return;
                if (!/^[A-Za-z0-9\-_.]+$/.test(val)) { document.getElementById('bulk-serial-error').style.display = 'block'; document.getElementById('bulk-serial-error').textContent = 'Invalid serial: ' + val; return; }
                // avoid duplicates in tag list
                const existing = Array.from(document.querySelectorAll('#bulkSerialTags .bulk-tag')).map(t => t.dataset.value);
                if (existing.indexOf(val) !== -1) return;
                // avoid duplicates across rows
                let globalExisting = getAllSerials();
                // if currentBulkRow has inline tags, exclude its own existing items from globalExisting
                if (currentBulkRow) {
                    let curItems = getRowTags(currentBulkRow);
                    if (curItems && curItems.length) {
                        globalExisting = globalExisting.filter(e => curItems.indexOf(e) === -1);
                    } else {
                        let curTa = currentBulkRow.querySelector('.serials-area');
                        if (curTa) {
                            let curItems2 = curTa.value.trim() === '' ? [] : curTa.value.trim().split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                            globalExisting = globalExisting.filter(e => curItems2.indexOf(e) === -1);
                        }
                    }
                }
                if (globalExisting.indexOf(val) !== -1) { document.getElementById('bulk-serial-error').style.display = 'block'; document.getElementById('bulk-serial-error').textContent = 'Duplicate serial: ' + val; return; }

                const span = document.createElement('span');
                span.className = 'bulk-tag';
                span.dataset.value = val;
                span.innerHTML = `<span class="text">${val}</span><button type="button" class="remove" aria-label="Remove">×</button>`;
                span.querySelector('.remove').addEventListener('click', function () { span.remove(); });
                document.getElementById('bulkSerialTags').appendChild(span);
                document.getElementById('bulk-serial-error').style.display = 'none';
            }

            // handle Enter and paste on the tag input
            const tagInput = document.getElementById('bulkSerialTagInput');
            tagInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const v = this.value.trim();
                    if (!v) return;
                    // accept multiple values separated by comma/newline
                    const parts = v.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                    parts.forEach(p => addBulkTag(p));
                    this.value = '';
                }
            });
            tagInput.addEventListener('paste', function (e) {
                setTimeout(() => {
                    const raw = this.value;
                    this.value = '';
                    if (!raw) return;
                    const parts = raw.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                    parts.forEach(p => addBulkTag(p));
                }, 10);
            });

            // parse uploaded CSV/text file for serials
            const fileInput = document.getElementById('bulkSerialFile');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const f = (e.target && e.target.files && e.target.files[0]) || null;
                    if (!f) return;
                    const reader = new FileReader();
                    reader.onload = function (evt) {
                        const txt = evt.target.result || '';
                        const parts = txt.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                        parts.forEach(p => addBulkTag(p));
                    };
                    reader.readAsText(f);
                    // clear selection so the same file can be reselected if needed
                    setTimeout(() => { try { fileInput.value = ''; } catch (e) {} }, 300);
                });
            }

                // when modal is shown, populate tags from currentBulkRow (or textarea fallback)
            $('#bulkSerialModal').on('shown.bs.modal', function (e) {
                // clear tag UI
                document.getElementById('bulkSerialTags').innerHTML = '';
                document.getElementById('bulk-serial-error').style.display = 'none';
                let source = document.getElementById('bulkSerialsInput').value || '';
                if (currentBulkRow) {
                    // prefer inline tags
                    const tags = getRowTags(currentBulkRow);
                    if (tags && tags.length) source = tags.join('\n');
                    else {
                        const curTa = currentBulkRow.querySelector('.serials-area');
                        if (curTa) source = curTa.value || '';
                        else {
                            const productId = currentBulkRow.querySelector("input[name='product_id[]']").value;
                            const hidden = Array.from(currentBulkRow.querySelectorAll(`input[name='serials-${productId}[]']`)).map(h=>h.value);
                            if (hidden.length) source = hidden.join('\n');
                        }
                    }
                }
                const items = source.trim() === '' ? [] : source.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                items.forEach(i => addBulkTag(i));
                // focus input for quick typing or paste
                tagInput.focus();
                // reset file input
                try { document.getElementById('bulkSerialFile').value = ''; } catch(e){}
            });

            // removed redundant textarea-focus handler (we now focus the tag input)

            document.getElementById('add_product_btn').addEventListener('click', function () {
                // Stop the pulse animation after first use
                this.classList.remove('pulse-hint');

                let productSelect = document.getElementById('product');
                let selected = productSelect.value;
                if (!selected) { showAlert('Please select a product to add', 'danger', 4000); return; }

                // Prevent adding same product twice
                let existing = Array.from(document.querySelectorAll("input[name='product_id[]']")).map(i => i.value);
                if (existing.indexOf(selected) !== -1) {
                    if (typeof toastr !== 'undefined' && toastr.warning) {
                        toastr.warning('Product already added');
                    } else {
                        showAlert('Product already added', 'warning', 4000);
                    }
                    return;
                }

                let option = productSelect.options[productSelect.selectedIndex];
                let title = option.text;
                let isSerial = option.getAttribute('data-is_serial') == 1 || option.getAttribute('data-is_serial') == '1';
                let isWarranty = option.getAttribute('data-is_warranty') == 1 || option.getAttribute('data-is_warranty') == '1';

                rowCounter++;
                let idx = rowCounter;

                let warrantyHtml = isWarranty ? `<input type="number" name="warranty[]" class="form-control warranty form-control-sm" min="0">` : '';
                let serialsHtml = '';
                if (isSerial) {
                    serialsHtml = `
                        <div>
                            <div class="row-bulk-container">
                                <div class="row-bulk-tags bulk-tags" data-product-id="${selected}"></div><br>
                                <div style="margin-top:6px"><button type="button" class="btn btn-sm btn-secondary bulk-serial-btn">Add Serials</button></div>
                            </div>
                        </div>`;
                }

                let row = document.createElement('tr');
                row.innerHTML = `
                                <td class="row-index">${idx}</td>
                                <td>
                                    <input type="hidden" name="product_id[]" value="${selected}">
                                    ${title}
                                </td>
                                <td><input type="number" name="unit_price[]" class="form-control unit_price form-control-sm" min="0" step="0.01" value="0"></td>
                                <td><input type="number" name="quantity[]" class="form-control quantity form-control-sm" min="1" value="1"></td>
                                <td><input type="text" name="total[]" class="form-control" value="0" readonly></td>
                                <td>${warrantyHtml}</td>
                                <td>${serialsHtml}<div class="field-error row-serial-error" style="display:none"></div></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                            `;

                // annotate row with product id and serial flag before inserting
                row.setAttribute('data-product-id', selected);
                row.setAttribute('data-is_serial', isSerial ? '1' : '0');
                document.getElementById('ptablebody_redesign').appendChild(row);
                updateLineCount();
                updateSubmitState();

                bindRowEvents(row);

                $('#product').val(null).trigger('change');
            });

            ['supplier', 'invoice_no', 'date_of_purchase'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) { el.addEventListener('input', updateSubmitState); el.addEventListener('change', updateSubmitState); }
            });

            document.getElementById('purchase_form_redesign').addEventListener('submit', function (e) {
                let valid = true;
                document.querySelectorAll('.row-serial-error').forEach(el => el.style.display = 'none');

                // validate numeric fields per row (price, qty, warranty)
                    document.querySelectorAll('#ptablebody_redesign tr').forEach(function (r) {
                    const priceEl = r.querySelector('.unit_price');
                    const qtyEl = r.querySelector('.quantity');
                    const warrantyEl = r.querySelector('.warranty');
                    const errEl = r.querySelector('.row-serial-error');
                    if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }

                    let price = priceEl ? parseFloat(priceEl.value) : 0;
                    let qty = qtyEl ? parseInt(qtyEl.value) : 0;
                    if (isNaN(price) || price < 0) {
                        valid = false;
                        if (errEl) { errEl.textContent = 'Unit price must be a number >= 0'; errEl.style.display = 'block'; }
                    }
                    // enforce quantity >= 1 for each row
                    if (isNaN(qty) || qty < 1) {
                        valid = false;
                        if (errEl) { errEl.textContent = (errEl.textContent ? errEl.textContent + ' | ' : '') + 'Quantity must be at least 1'; errEl.style.display = 'block'; }
                    }
                    if (warrantyEl) {
                        let w = warrantyEl.value === '' ? null : parseInt(warrantyEl.value);
                        if (w === null || isNaN(w) || w < 0) {
                            valid = false;
                            if (errEl) { errEl.textContent = (errEl.textContent ? errEl.textContent + ' | ' : '') + 'Warranty must be >= 0'; errEl.style.display = 'block'; }
                        }
                    }

                });

                // if numeric validation failed, stop before serial checks
                if (!valid) { e.preventDefault(); const first = document.querySelector('.row-serial-error[style*="block"]'); if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' }); return; }

                // now validate serials match qty and convert row tags or textareas into hidden inputs
                document.querySelectorAll('#ptablebody_redesign tr').forEach(function (r) {
                    let qty = parseInt(r.querySelector('.quantity').value) || 0;
                    // prefer inline tags
                    const tags = getRowTags(r);
                    if (tags && tags.length) {
                        if (tags.length !== qty) {
                            valid = false;
                            let err = r.querySelector('.row-serial-error');
                            err.textContent = `Serial count (${tags.length}) must match quantity (${qty})`;
                            err.style.display = 'block';
                        } else {
                            let productId = r.querySelector("input[name='product_id[]']").value;
                            // create hidden inputs
                            tags.forEach(function (s) {
                                let inp = document.createElement('input');
                                inp.type = 'hidden'; inp.name = `serials-${productId}[]`; inp.value = s;
                                r.querySelector('td:nth-child(7)').appendChild(inp);
                            });
                            try {
                                let jsonInp = document.createElement('input');
                                jsonInp.type = 'hidden';
                                jsonInp.name = `serials_json[${productId}]`;
                                jsonInp.value = JSON.stringify(tags);
                                r.querySelector('td:nth-child(7)').appendChild(jsonInp);
                            } catch (e) { }
                        }
                    } else {
                        // fallback to old textarea behavior
                        let serialsArea = r.querySelector('.serials-area');
                        if (serialsArea) {
                            let raw = serialsArea.value.trim();
                            let items = raw === '' ? [] : raw.split(/[,\n\r]+/).map(s => s.trim()).filter(Boolean);
                            if (items.length !== qty) {
                                valid = false;
                                let err = r.querySelector('.row-serial-error');
                                err.textContent = `Serial count (${items.length}) must match quantity (${qty})`;
                                err.style.display = 'block';
                            } else {
                                let productId = r.querySelector("input[name='product_id[]']").value;
                                serialsArea.remove();
                                items.forEach(function (s) {
                                    let inp = document.createElement('input');
                                    inp.type = 'hidden'; inp.name = `serials-${productId}[]`; inp.value = s;
                                    r.querySelector('td:nth-child(7)').appendChild(inp);
                                });
                                try {
                                    let jsonInp = document.createElement('input');
                                    jsonInp.type = 'hidden';
                                    jsonInp.name = `serials_json[${productId}]`;
                                    jsonInp.value = JSON.stringify(items);
                                    r.querySelector('td:nth-child(7)').appendChild(jsonInp);
                                } catch (e) { }
                            }
                        }
                    }
                });

                if (!valid) { e.preventDefault(); let first = document.querySelector('.row-serial-error[style*="block"]'); if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
            });

        });
    </script>
@endpush
