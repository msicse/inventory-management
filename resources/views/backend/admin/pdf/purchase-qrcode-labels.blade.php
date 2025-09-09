<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase QR Code Labels</title>
    <style>
        @page {
            size: 1.4in 1.4in;
            margin: 0.05in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            background: white;
        }

        .page {
            width: 100%;
            height: 100%;
            page-break-after: always;
            position: relative;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .label {
            width: 100%;
            height: 100%;
            border: 1px solid black;
            padding: 0.02in;
            text-align: center;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
        }

        .qrcode-section {
            width: 100%;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            margin-top: 0.07in;
            margin-bottom: 0.02in;
        }

        .qrcode-section svg {
            width: 1.0in !important;
            height: 1.0in !important;
            background: white;
            margin: 0 auto;
            display: block;
        }

        .qrcode-section img {
            width: 1.0in !important;
            height: 1.0in !important;
            background: white;
            margin: 0 auto;
            display: block;
        }

        .qr-fallback {
            width: 1.0in;
            height: 1.0in;
            border: 2px solid black;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6px;
            background: white;
            line-height: 1;
            font-weight: bold;
        }

        .asset-tag {
            font-weight: bold;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.1;
            background: white;
            color: black;
            text-align: center;
            margin-top: 0.05in;
            max-height: 0.25in;
            overflow: hidden;
        }

        @media print {
            * {
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
    @foreach($qrCodeData as $index => $item)
    <div class="page">
        <div class="label">
            <div class="qrcode-section">
                @if(isset($item['qrCodeBase64']) && $item['qrCodeBase64'])
                    <img src="{{ $item['qrCodeBase64'] }}" alt="QR Code">
                @elseif(isset($item['qrCodeHtml']) && $item['qrCodeHtml'])
                    <div class="qr-fallback">
                        {!! $item['qrCodeHtml'] !!}
                    </div>
                @else
                    <div class="qr-fallback">
                        NO QR DATA
                    </div>
                @endif
            </div>
            <div class="asset-tag">{{ $item['assetTag'] }}</div>
        </div>
    </div>
    @endforeach
</body>
</html>
