<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Purchase QR Code + Barcode Combo Labels</title>
    <style>
        @page {
            margin: 0;
            size: 100.8pt 180pt;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100.8pt;
            background: #fff;
            font-family: Arial, sans-serif;
        }

        body {
            font-size: 0;
            line-height: 1;
        }

        .page {
            width: 100.8pt;
            height: 180pt;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }

        .page:last-of-type {
            page-break-after: auto !important;
        }

        .content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 4pt 5pt 4pt 5pt;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .qr-box {
            width: 100%;
            height: 68pt;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
        }

        .qr-inner {
            width: 68pt;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .qr-box img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        .qr-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font: 700 8pt Arial;
        }

        .asset-tag {
            width: 100%;
            height: 22pt;
            display: flex;
            align-items: center;
            justify-content: center;
            font: 600 16pt/16pt Arial;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }

        .barcode-box {
            width: 100%;
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .barcode-inner-fixed {
            width: 80pt;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .barcode-wrapper {
            display: inline-block;
            max-width: 100%;
        }

        .barcode-wrapper br {
            display: none;
        }

        .barcode-box img,
        .barcode-box svg {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .barcode-box table {
            margin: 0 auto !important;
            border-collapse: collapse;
            width: 100% !important;
        }

        .barcode-box td {
            padding: 0 !important;
        }

        .barcode-fallback {
            font: 400 8pt Arial;
        }
    </style>
</head>

<body>
    @foreach($qrCodeData as $item)
        <div class="page">
            <div class="label">
                <div class="center-wrapper">
                    <div class="qr-box">
                        <div class="qr-inner">
                            @if(!empty($item['qrCodeBase64']))
                                <img src="{{ $item['qrCodeBase64'] }}" alt="QR Code">
                            @elseif(!empty($item['qrCodeHtml']))
                                {!! $item['qrCodeHtml'] !!}
                            @else
                                <div class="qr-fallback">NO QR</div>
                            @endif
                        </div>
                    </div>
                    <div class="asset-tag">{{ $item['assetTag'] ?? 'ASSET TAG' }}</div>
                    <div class="barcode-box">
                        <div class="barcode-inner-fixed">
                            @if(!empty($item['barcodeHTML']))
                                <div class="barcode-wrapper">{!! $item['barcodeHTML'] !!}</div>
                            @elseif(!empty($item['barcodeSVG']))
                                {!! $item['barcodeSVG'] !!}
                            @else
                                <div class="barcode-fallback">NO BARCODE</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
