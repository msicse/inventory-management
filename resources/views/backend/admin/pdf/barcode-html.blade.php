<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode</title>
    <style>
        @page {
            size: 3.5in 1.4in;
            margin: 0.1in;
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
            padding: 0.1in;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: white;
        }

        .barcode-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background: white;
            padding: 0.05in 0;
        }

        .barcode-section img {
            max-width: 3in;
            max-height: 0.9in;
            width: auto;
            height: auto;
            background: white;
        }

        .barcode-section svg {
            max-width: 3in;
            max-height: 0.9in;
            width: auto;
            height: auto;
            background: white;
        }

        .asset-tag {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.05in;
            line-height: 1;
            background: white;
            color: black;
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="barcode-section">
            {!! $barcodeHTML !!}
        </div>
        <div class="asset-tag">{{ $assetTag }}</div>
    </div>
</body>
</html>
