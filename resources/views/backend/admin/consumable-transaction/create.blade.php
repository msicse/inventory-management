@extends('layouts.backend.app')

@section('title','Consumable Distribution | Add')

@push('css')
<link href="{{ asset('backend/select2/select2.min.css') }}" rel="stylesheet" />
<style>
    .entry-shell {
        background: linear-gradient(140deg, #f4f7fb 0%, #eef4ff 55%, #f9f5ef 100%);
        border-radius: 14px;
        padding: 24px;
        border: 1px solid #dbe4ef;
    }

    .entry-title {
        font-size: 22px;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0;
    }

    .entry-subtitle {
        color: #607d9b;
        margin: 8px 0 0 0;
    }

    .hint-strip {
        margin-top: 18px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #eaf6ff;
        border-left: 4px solid #1e88e5;
        color: #1f4d7a;
        font-size: 13px;
    }

    .quick-stats {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .quick-pill {
        background: #fff;
        border: 1px solid #d7e1ef;
        border-radius: 999px;
        padding: 8px 14px;
        color: #39556f;
        font-size: 12px;
        font-weight: 600;
    }

    .open-panel-btn {
        margin-top: 18px;
        min-width: 220px;
    }

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

    .mini-note {
        font-size: 12px;
        color: #607d9b;
        margin-top: 4px;
    }

    .stock-chip {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        background: #f2f8f2;
        color: #2e7d32;
        border: 1px solid #cae7cb;
        font-weight: 600;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .entry-shell {
            padding: 16px;
        }

        .panel-body {
            padding: 14px 14px 90px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="entry-shell">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <h2 class="entry-title">Consumable Quick Issue</h2>
                <p class="entry-subtitle">Fast, minimum-field distribution workflow for consumable products.</p>
                <div class="hint-strip">
                    Use the issue panel to choose type, product, employee, and quantity. Issue date is set automatically to today.
                </div>
                <div class="quick-stats">
                    <span class="quick-pill">Minimum Fields</span>
                    <span class="quick-pill">Stock-Aware Quantity</span>
                    <span class="quick-pill">Optional Note</span>
                </div>
                <button type="button" class="btn btn-primary btn-lg waves-effect open-panel-btn" id="openIssuePanel">
                    <i class="material-icons" style="vertical-align: middle;">launch</i>
                    Open Issue Panel
                </button>
            </div>
            <div class="col-md-4 col-sm-12 text-right" style="margin-top:8px;">
                <a href="{{ route('consumable.transections.index') }}" class="btn btn-default waves-effect">
                    <i class="material-icons">keyboard_return</i>
                    Back To List
                </a>
            </div>
        </div>
    </div>

    <div id="panelBackdrop" class="panel-backdrop"></div>

    <div id="issuePanel" class="slide-panel" aria-hidden="true">
        <div class="panel-head">
            <h3 class="panel-title">Issue Consumable</h3>
            <button type="button" class="btn btn-link" id="closeIssuePanel" style="text-decoration:none;">
                <i class="material-icons">close</i>
            </button>
        </div>

        <form action="{{ route('consumable.transections.store') }}" method="post" id="store_form">
            @csrf
            <input type="hidden" name="date_of_issue" id="date_of_issue" value="{{ old('date_of_issue', now()->format('d-m-Y')) }}">
            <input id="remain" type="hidden">

            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Product Type</label>
                    <select name="product_type" id="product_type" class="form-control" required>
                        <option value="">Select product type</option>
                        @foreach($types as $data)
                            <option value="{{ $data->id }}" {{ $data->id == old('product_type') ? 'selected' : '' }}>{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Product</label>
                    <select class="form-control" name="product" id="product" required>
                        <option value="">Select product</option>
                    </select>
                    <span class="stock-chip" id="stocksInfo" style="display:none;"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Employee</label>
                    <select name="employee" id="employee" class="form-control" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $data)
                            <option value="{{ $data->id }}" {{ $data->id == old('employee') ? 'selected' : '' }}>{{ $data->name . ' - ' . sprintf('%03d', $data->emply_id) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Issue Quantity</label>
                    <input type="number" min="1" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" required>
                    <div class="mini-note" id="qtyHint">Quantity will be validated against available stock.</div>
                </div>

                <div class="form-group">
                    <a href="#optionalNote" data-toggle="collapse" aria-expanded="false" aria-controls="optionalNote">
                        Add optional note
                    </a>
                    <div class="collapse" id="optionalNote" style="margin-top:8px;">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Optional issue note">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel-foot">
                <button type="button" class="btn btn-default waves-effect" id="cancelIssue">Cancel</button>
                <button type="submit" class="btn btn-success waves-effect">
                    <i class="material-icons" style="vertical-align: middle;">check</i>
                    Confirm Issue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>

<script>
const oldProductId = '{{ old('product') }}';

function validateIssueQuantity() {
    let qty = parseInt($('#quantity').val(), 10);
    let available = parseInt($('#remain').val(), 10);

    if (isNaN(qty) || qty <= 0 || isNaN(available)) {
        return;
    }

    if (qty > available) {
        $('#quantity').val(available > 0 ? available : 1);
        toastr.warning('Issue quantity cannot exceed available stock.');
    }
}

function openPanel() {
    $('#panelBackdrop').addClass('visible');
    $('#issuePanel').addClass('visible').attr('aria-hidden', 'false');
    $('body').css('overflow', 'hidden');
}

function closePanel() {
    $('#panelBackdrop').removeClass('visible');
    $('#issuePanel').removeClass('visible').attr('aria-hidden', 'true');
    $('body').css('overflow', '');
}

$(document).ready(function() {
    $('#product_type, #product, #employee').select2({ width: '100%' });
    $('#store_form').validate();

    $('#openIssuePanel').on('click', function() {
        openPanel();
    });

    $('#closeIssuePanel, #cancelIssue, #panelBackdrop').on('click', function() {
        closePanel();
    });

    if ('{{ old('product_type') }}' || '{{ old('employee') }}' || '{{ old('quantity') }}') {
        openPanel();
    }

    $('#product_type').on('change', function(e) {
        e.preventDefault();

        var typeId = $(this).val();
        var url = '{{ url('typed-consumable-products') }}/' + typeId;

        $('#product').empty().append('<option value="">Loading...</option>').trigger('change');
        $('#stocksInfo').hide();
        $('#remain').val('');

        if (!typeId) {
            $('#product').empty().append('<option value="">Select product</option>').trigger('change');
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#product').empty().append('<option value="">Select product</option>');

                if (data && data.products && data.products.length) {
                    $.each(data.products, function(key, item) {
                        let label = item.title + ' - ' + item.brand + ' - ' + item.model + ' (Qty: ' + (item.quantity || 0) + ')';
                        $('#product').append('<option value="' + item.id + '" data-available="' + (item.quantity || 0) + '">' + label + '</option>');
                    });

                    if (oldProductId) {
                        $('#product').val(oldProductId);
                    }
                } else {
                    $('#product').append('<option value="">No stock available</option>');
                }

                $('#product').trigger('change');
            },
            error: function() {
                $('#product').empty().append('<option value="">Failed to load products</option>').trigger('change');
            }
        });
    });

    $('#product').on('change', function() {
        let selected = $('#product option:selected');
        let available = parseInt(selected.data('available') || 0, 10);

        if (!isNaN(available) && available >= 0) {
            $('#remain').val(available);
            $('#stocksInfo').text('Available quantity: ' + available).show();
            $('#qtyHint').text('You can issue up to ' + available + ' units.');
            validateIssueQuantity();
        } else {
            $('#stocksInfo').hide();
            $('#qtyHint').text('Quantity will be validated against available stock.');
        }
    });

    $('#quantity').on('input', function() {
        validateIssueQuantity();
    });

    if ($('#product_type').val()) {
        $('#product_type').trigger('change');
    }
});
</script>
@endpush
