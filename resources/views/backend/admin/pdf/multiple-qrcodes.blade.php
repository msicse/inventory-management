<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Multiple QR Codes</title>
    <style>
        @page {
            size: A4;
            margin: 0.25in;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .qrcode-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.1in;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .qrcode-container {
            width: 3.8in;
            height: 1.8in;
            border: 2px solid #000;
            padding: 0.1in;
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

        .qrcode {
            margin-bottom: 0.1in;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .qrcode svg {
            max-width: 1.4in;
            max-height: 1.4in;
            width: auto;
            height: auto;
        }

        .asset-tag {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0;
            padding: 0.05in 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .qr-label {
            font-size: 10px;
            color: #666;
            margin-top: 0.02in;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .qrcode-container {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .qrcode-grid {
                gap: 0.05in;
            }
        }
    </style>
</head>
<body>
    <div class="qrcode-grid">
        @foreach($qrCodeData as $item)
        <div class="qrcode-container">
            <div class="qrcode">
                {!! $item['qrcode'] !!}
            </div>
            <div class="asset-tag">{{ $item['assetTag'] }}</div>
            <div class="qr-label">QR CODE</div>
        </div>
        @endforeach
    </div>
</body>
</html>
