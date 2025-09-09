@extends('backend.layouts.master')

@section('title')
QR Code Generator - Admin Panel
@endsection

@section('styles')
<style>
    .qr-preview {
        border: 1px solid #ddd;
        padding: 20px;
        text-align: center;
        background: #f9f9f9;
        border-radius: 8px;
    }
    .qr-options {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 15px;
    }
    .btn-group {
        margin: 10px 0;
    }
</style>
@endsection

@section('admin-content')
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">QR Code Generator</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>QR Code Generator</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>

<div class="main-content-inner">
    <div class="row">
        <!-- QR Code Options -->
        <div class="col-lg-4">
            <div class="qr-options">
                <h5 class="mb-3">QR Code Generator</h5>

                <!-- QR Type Selection -->
                <div class="form-group">
                    <label>QR Code Type:</label>
                    <select class="form-control" id="qrType">
                        <option value="url">URL</option>
                        <option value="text">Plain Text</option>
                        <option value="wifi">WiFi</option>
                        <option value="contact">Contact (vCard)</option>
                    </select>
                </div>

                <!-- URL QR Code -->
                <div id="urlOptions" class="qr-type-options">
                    <div class="form-group">
                        <label>URL:</label>
                        <input type="url" class="form-control" id="qrUrl" placeholder="https://example.com">
                    </div>
                </div>

                <!-- Text QR Code -->
                <div id="textOptions" class="qr-type-options" style="display: none;">
                    <div class="form-group">
                        <label>Text:</label>
                        <textarea class="form-control" id="qrText" rows="3" placeholder="Enter your text here"></textarea>
                    </div>
                </div>

                <!-- WiFi QR Code -->
                <div id="wifiOptions" class="qr-type-options" style="display: none;">
                    <div class="form-group">
                        <label>Network Name (SSID):</label>
                        <input type="text" class="form-control" id="wifiSSID" placeholder="WiFi Network Name">
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="text" class="form-control" id="wifiPassword" placeholder="WiFi Password">
                    </div>
                    <div class="form-group">
                        <label>Security:</label>
                        <select class="form-control" id="wifiSecurity">
                            <option value="WPA">WPA/WPA2</option>
                            <option value="WEP">WEP</option>
                            <option value="nopass">No Password</option>
                        </select>
                    </div>
                </div>

                <!-- Contact QR Code -->
                <div id="contactOptions" class="qr-type-options" style="display: none;">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" class="form-control" id="contactName" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" class="form-control" id="contactPhone" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" class="form-control" id="contactEmail" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <label>Organization:</label>
                        <input type="text" class="form-control" id="contactOrg" placeholder="Company/Organization">
                    </div>
                    <div class="form-group">
                        <label>Website:</label>
                        <input type="url" class="form-control" id="contactWebsite" placeholder="https://website.com">
                    </div>
                </div>

                <!-- QR Options -->
                <div class="form-group">
                    <label>Size:</label>
                    <select class="form-control" id="qrSize">
                        <option value="150">Small (150px)</option>
                        <option value="200" selected>Medium (200px)</option>
                        <option value="300">Large (300px)</option>
                        <option value="400">Extra Large (400px)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Format:</label>
                    <select class="form-control" id="qrFormat">
                        <option value="svg">SVG (Vector)</option>
                        <option value="png">PNG (Image)</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="generateQrCode()">
                        <i class="fa fa-qrcode"></i> Generate QR Code
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadQrCode()" id="downloadBtn" style="display: none;">
                        <i class="fa fa-download"></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- QR Code Preview -->
        <div class="col-lg-8">
            <div class="qr-preview">
                <h5>QR Code Preview</h5>
                <div id="qrPreview" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <p class="text-muted">Generate a QR code to see preview</p>
                </div>
                <div id="qrData" style="margin-top: 20px; display: none;">
                    <h6>QR Code Data:</h6>
                    <pre id="qrDataContent" style="background: #f8f9fa; padding: 10px; border-radius: 4px;"></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock QR Codes Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Generate QR Codes for Inventory Items</h5>
                </div>
                <div class="card-body">
                    <p>You can also generate QR codes for specific inventory items:</p>
                    <div class="form-group">
                        <label>Stock ID:</label>
                        <input type="number" class="form-control" id="stockId" placeholder="Enter Stock ID" style="max-width: 200px; display: inline-block;">
                        <button type="button" class="btn btn-info ml-2" onclick="generateStockQrCode()">
                            <i class="fa fa-qrcode"></i> Generate Stock QR
                        </button>
                        <button type="button" class="btn btn-secondary ml-2" onclick="printStockQrCode()">
                            <i class="fa fa-print"></i> Print Label
                        </button>
                    </div>
                    <div id="stockQrPreview" style="margin-top: 20px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// QR Type Selection
document.getElementById('qrType').addEventListener('change', function() {
    const type = this.value;
    document.querySelectorAll('.qr-type-options').forEach(el => el.style.display = 'none');
    document.getElementById(type + 'Options').style.display = 'block';
});

// Generate QR Code
function generateQrCode() {
    const type = document.getElementById('qrType').value;
    const size = document.getElementById('qrSize').value;
    const format = document.getElementById('qrFormat').value;

    let data = '';
    let url = '';

    switch(type) {
        case 'url':
            data = document.getElementById('qrUrl').value;
            if (!data) {
                alert('Please enter a URL');
                return;
            }
            url = '{{ route("qrcode.url") }}';
            break;
        case 'text':
            data = document.getElementById('qrText').value;
            if (!data) {
                alert('Please enter some text');
                return;
            }
            url = '{{ route("qrcode.custom") }}';
            break;
        case 'wifi':
            const ssid = document.getElementById('wifiSSID').value;
            const password = document.getElementById('wifiPassword').value;
            const security = document.getElementById('wifiSecurity').value;
            if (!ssid) {
                alert('Please enter WiFi network name');
                return;
            }
            data = `WIFI:T:${security};S:${ssid};P:${password};;`;
            url = '{{ route("qrcode.custom") }}';
            break;
        case 'contact':
            const name = document.getElementById('contactName').value;
            const phone = document.getElementById('contactPhone').value;
            const email = document.getElementById('contactEmail').value;
            const org = document.getElementById('contactOrg').value;
            const website = document.getElementById('contactWebsite').value;

            if (!name) {
                alert('Please enter contact name');
                return;
            }

            data = `BEGIN:VCARD\nVERSION:3.0\nFN:${name}\nORG:${org}\nTEL:${phone}\nEMAIL:${email}\nURL:${website}\nEND:VCARD`;
            url = '{{ route("qrcode.custom") }}';
            break;
    }

    // Make AJAX request
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            [type === 'url' ? 'url' : 'data']: data,
            format: format,
            size: size,
            ajax: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qrPreview').innerHTML = data.qrcode;
            document.getElementById('qrDataContent').textContent = typeof data.data === 'object' ? JSON.stringify(data.data, null, 2) : data.data;
            document.getElementById('qrData').style.display = 'block';
            document.getElementById('downloadBtn').style.display = 'inline-block';
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error generating QR code: ' + error.message);
    });
}

// Generate Stock QR Code
function generateStockQrCode() {
    const stockId = document.getElementById('stockId').value;
    if (!stockId) {
        alert('Please enter a Stock ID');
        return;
    }

    fetch(`/admin/stock/${stockId}/qrcode?ajax=1&type=simple`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('stockQrPreview').innerHTML = `
                <div style="border: 1px solid #ddd; padding: 20px; text-align: center;">
                    <h6>Stock QR Code (ID: ${data.stock_id})</h6>
                    ${data.qrcode}
                    <p style="margin-top: 10px;"><small>${data.data}</small></p>
                </div>
            `;
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error generating stock QR code: ' + error.message);
    });
}

// Print Stock QR Code
function printStockQrCode() {
    const stockId = document.getElementById('stockId').value;
    if (!stockId) {
        alert('Please enter a Stock ID');
        return;
    }

    window.open(`/admin/stock/${stockId}/print-qrcode`, '_blank');
}

// Download QR Code (placeholder - would need server-side implementation)
function downloadQrCode() {
    alert('Download functionality would be implemented based on the generated QR code');
}
</script>
@endsection
