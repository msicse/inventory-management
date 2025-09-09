<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code Label</title>
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

        .label {
            width: 100%;
            height: 100%;
            border: 1px solid black;
            padding: 0.02in;
            text-align: center;
            background: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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

        .qr-html-fallback {
            margin: 0 auto;
            border-collapse: collapse;
            width: 1.0in;
            height: 1.0in;
        }

        .qr-html-fallback td {
            padding: 0;
            margin: 0;
            border: none;
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
    </style>
</head>
<body>
    <div class="label">
        <div class="qrcode-section">
            @if(isset($qrCodeBase64) && $qrCodeBase64)
                <img src="{{ $qrCodeBase64 }}" alt="QR Code">
            @elseif($qrCodeSvg)
                {!! $qrCodeSvg !!}
            @elseif(isset($qrCodeHtml) && $qrCodeHtml)
                <div class="qr-html-fallback">
                    {!! $qrCodeHtml !!}
                </div>
            @endif
        </div>
        <div class="asset-tag">{{ $assetTag }}</div>
    </div>
</body>
</html>
