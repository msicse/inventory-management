<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode - {{ $stock->asset_tag }}</title>
    <style>
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .barcode-container {
            width: 250px;
            height: 120px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }
        .barcode {
            margin-bottom: 5px;
        }
        .asset-tag {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        .company-name {
            font-size: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="barcode-container">
        <div class="company-name">RSC</div>
        <div class="barcode">
            @if(isset($barcode))
                <img src="{{ $barcode }}" alt="Barcode">
            @else
                {!! DNS1D::getBarcodeHTML($barcodeData, 'C128', 3, 60) !!}
            @endif
        </div>
        <div class="asset-tag">{{ $assetTag }}</div>
    </div>
</body>
</html>
