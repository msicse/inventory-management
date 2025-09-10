<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase QR Code + Barcode Combo Labels</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: 1.4in 2.5in;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }

        .label {
            width: 1.4in;
            height: calc(2.5in - 8px); /* adjust so padding doesn't overflow */
            padding: 4px;
            background: white;
            display: block;
            page-break-after: always;
        }

        .label:last-child {
            page-break-after: auto; /* prevents extra blank page */
        }

        .qr-section {
            width: 100%;
            height: 1.2in;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
        }

        .qr-section img {
            max-width: 1.0in;
            max-height: 1.0in;
            width: auto;
            height: auto;
        }

        .qr-fallback {
            width: 1.0in;
            height: 1.0in;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: bold;
        }

        .barcode-section {
            width: 100%;
            height: 1.1in;
            display: block;
        }

        .barcode-container {
            width: 100%;
            height: 0.7in;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
            text-align: center;
        }

        /* ensure barcode centers regardless of generator output */
        .barcode-container svg,
        .barcode-container img,
        .barcode-container table {
            margin: auto;
            display: block;
        }

        .asset-tag-bottom {
            width: 100%;
            height: 0.35in;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            background: white;
            border: 1px solid #333;
        }
    </style>
</head>
<body>
    @foreach($qrCodeData as $item)
    <div class="label">
        <!-- QR Code Section -->
        <div class="qr-section">
            @if(isset($item['qrCodeBase64']) && $item['qrCodeBase64'])
                <img src="{{ $item['qrCodeBase64'] }}" alt="QR Code">
            @else
                <div class="qr-fallback">NO QR</div>
            @endif
        </div>

        <!-- Barcode Section -->
        <div class="barcode-section">
            <div class="barcode-container">
                @if(isset($item['barcodeHTML']) && $item['barcodeHTML'])
                    {!! $item['barcodeHTML'] !!}
                @else
                    <div style="padding: 4px; font-size: 10px;">NO BARCODE</div>
                @endif
            </div>
            <div class="asset-tag-bottom">{{ $item['assetTag'] ?? 'ASSET TAG' }}</div>
        </div>
    </div>
    @endforeach
</body>
</html>
