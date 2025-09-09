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

        body {
            font-family: Arial, sans-serif;
            background: white;
        }

        .label {
            width: 1.3in;
            height: 1.3in;
            border: 1px solid black;
            padding: 0.05in;
            text-align: center;
            background: white;
            display: block;
            page-break-after: always;
            margin: 0 auto;
        }

        .label:last-child {
            page-break-after: avoid;
        }

        .qr-container {
            width: 100%;
            height: 1.0in;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0.05in;
        }

        .qr-container img {
            width: 1.0in;
            height: 1.0in;
            display: block;
        }

        .qr-container svg {
            width: 1.0in !important;
            height: 1.0in !important;
            display: block;
        }

        .asset-tag {
            font-weight: bold;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: black;
            text-align: center;
            line-height: 1;
            height: 0.18in;
            overflow: hidden;
        }

        .qr-fallback {
            width: 1.0in;
            height: 1.0in;
            border: 2px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach($qrCodeData as $index => $item)
    <div class="label">
        <div class="qr-container">
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
    @endforeach
</body>
</html>
