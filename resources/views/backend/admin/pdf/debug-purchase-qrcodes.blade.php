<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Debug - Purchase QR Code Labels</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug-info { background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc; }
        .qr-test { border: 2px solid red; margin: 10px; padding: 10px; text-align: center; }
        .qr-test img { max-width: 200px; max-height: 200px; }
    </style>
</head>
<body>
    <h1>QR Code Debug - Purchase {{ $purchase->id ?? 'Unknown' }}</h1>

    <div class="debug-info">
        <strong>Total QR Codes to Generate:</strong> {{ count($qrCodeData) }}
    </div>

    @foreach($qrCodeData as $index => $item)
    <div class="qr-test">
        <h3>Item {{ $index + 1 }}: {{ $item['assetTag'] ?? 'No Asset Tag' }}</h3>

        <div class="debug-info">
            <strong>QR Data:</strong> {{ $item['qrData'] ?? 'No QR Data' }}<br>
            <strong>Has Base64:</strong> {{ isset($item['qrCodeBase64']) && $item['qrCodeBase64'] ? 'YES' : 'NO' }}<br>
            <strong>Has SVG:</strong> {{ isset($item['qrCodeSvg']) && $item['qrCodeSvg'] ? 'YES' : 'NO' }}<br>
            <strong>Has HTML:</strong> {{ isset($item['qrCodeHtml']) && $item['qrCodeHtml'] ? 'YES' : 'NO' }}
        </div>

        <div style="border: 1px solid blue; padding: 10px; margin: 10px;">
            @if(isset($item['qrCodeBase64']) && $item['qrCodeBase64'])
                <p><strong>Showing Base64 Image:</strong></p>
                <img src="{{ $item['qrCodeBase64'] }}" alt="QR Code Base64">
            @elseif(isset($item['qrCodeSvg']) && $item['qrCodeSvg'])
                <p><strong>Showing SVG:</strong></p>
                {!! $item['qrCodeSvg'] !!}
            @elseif(isset($item['qrCodeHtml']) && $item['qrCodeHtml'])
                <p><strong>Showing HTML Fallback:</strong></p>
                {!! $item['qrCodeHtml'] !!}
            @else
                <p style="color: red;"><strong>NO QR CODE DATA FOUND!</strong></p>
            @endif
        </div>
    </div>
    @endforeach
</body>
</html>
