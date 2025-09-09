<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase QR Codes - {{ $purchase->purchase_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #000;
        }

        .header p {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }

        .qr-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.2in;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .qr-container {
            width: 3.8in;
            height: 2.2in;
            border: 2px solid #000;
            padding: 0.15in;
            box-sizing: border-box;
            text-align: center;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: white;
            margin-bottom: 0.1in;
        }

        .qr-code {
            margin-bottom: 0.1in;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .qr-code img {
            max-width: 2.5in;
            max-height: 1.5in;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .asset-tag {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin: 0;
            padding: 0.05in 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid #ccc;
            width: 100%;
        }

        .stock-info {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
            text-align: center;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .qr-container {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .qr-grid {
                gap: 0.1in;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>QR Codes for Purchase #{{ $purchase->invoice_no }}</h1>
        <p>Generated on: {{ date('F j, Y g:i A') }}</p>
        <p>Total Items: {{ count($qrCodeData) }}</p>
    </div>

    <div class="qr-grid">
        @foreach($qrCodeData as $item)
        <div class="qr-container">
            <div class="qr-code">
                {!! $item['qrcode'] !!}
            </div>
            <div class="stock-info">{{ $item['stock']->product->name ?? 'N/A' }}</div>
            <div class="asset-tag">{{ $item['assetTag'] }}</div>
        </div>
        @endforeach
    </div>
</body>
</html>
