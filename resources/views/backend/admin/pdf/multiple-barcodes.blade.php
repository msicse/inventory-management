<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Multiple Barcodes</title>
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

        .barcode-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.1in;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .barcode-container {
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

        .barcode {
            margin-bottom: 0.1in;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .barcode img {
            max-width: 3.6in;
            max-height: 1.1in;
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
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .barcode-container {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .barcode-grid {
                gap: 0.05in;
            }
        }
    </style>
</head>
<body>
    <div class="barcode-grid">
        @foreach($barcodeData as $item)
        <div class="barcode-container">
            <div class="barcode">
                <img src="{{ $item['barcode'] }}" alt="Barcode">
            </div>
            <div class="asset-tag">{{ $item['assetTag'] }}</div>
        </div>
        @endforeach
    </div>
</body>
</html>
