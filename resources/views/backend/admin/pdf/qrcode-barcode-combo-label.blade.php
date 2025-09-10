<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code + Barcode Label</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: 1.4in 2.5in; /* Width: 1.4in, Height: 2.5in for better vertical layout */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }

        .page {
            width: 100%;
            height: 100%;
            position: relative;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .label {
            width: 100%;
            height: 100%;
            padding: 4px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .qr-section {
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
        }

        .qr-section svg,
        .qr-section img {
            width: 120px !important;
            height: 120px !important;
            background: white;
            display: block;
            margin: 0 auto;
        }

        .qr-fallback {
            width: 120px;
            height: 120px;
            text-align: center;
            font-size: 14px;
            background: white;
            line-height: 120px;
            font-weight: bold;
            margin: 0 auto;
        }

        .asset-tag-section {
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
            padding: 4px;
        }

        .asset-tag {
            font-size: 12px;
            font-weight: bold;
            color: black;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 14px;
        }

        .barcode-section {
            width: 100%;
            text-align: center;
            margin-bottom: 4px;
        }

        .barcode-container {
            width: 100%;
            height: 70px;
            text-align: center;
            margin-bottom: 6px;
            padding: 5px;
            line-height: 70px;
            background: white;
        }

        .barcode-container svg {
            height: 60px;
            width: auto;
            max-width: 95%;
            display: inline-block;
            vertical-align: middle;
        }

        .asset-tag-bottom {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            color: black;
            padding: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .clearfix {
            clear: both;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="label">
            <!-- QR Code Section (Top) -->
            <div class="qr-section">
                @if(isset($qrCodeBase64) && $qrCodeBase64)
                    <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                @else
                    <div class="qr-fallback">
                        NO QR
                    </div>
                @endif
            </div>

            <!-- Barcode Section (Middle) -->
            <div class="barcode-section">
                <div class="barcode-container">
                    @if(isset($barcodeHTML) && $barcodeHTML)
                        {!! $barcodeHTML !!}
                    @else
                        <div style="padding: 4px; font-size: 10px;">NO BARCODE</div>
                    @endif
                </div>
                <div class="asset-tag-bottom">{{ $assetTag ?? 'ASSET TAG' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
