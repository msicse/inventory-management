<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase QR Code + Barcode Combo Labels</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        @page { margin:0; size:1.4in 2.5in; }
        body { font-family: Arial, sans-serif; margin:0; padding:0; background:#fff; }

    /* Redesigned simple block layout */
    /* Each label forces a new page except the last to prevent trailing blank page */
    .label { width:1.4in; height:2.5in; padding:5px; box-sizing:border-box; background:#fff; position:relative; page-break-after:always; display:table; }
    body > .label:last-of-type { page-break-after:auto !important; }
    /* Extra safety: remove accidental breaks after the last element DomPDF sometimes inserts */
    body:after { content:""; display:none; }
    .center-wrapper { display:table-cell; vertical-align:middle; text-align:center; }

    .qr-box { width:100%; height:0.95in; display:flex; align-items:center; justify-content:center; }
    .fixed-width { width:0.95in; margin:0 auto; }
    .qr-inner { width:0.95in; height:100%; display:flex; align-items:center; justify-content:center; margin:0 auto; }
    .qr-box img { max-width:100%; max-height:100%; width:auto; height:auto; display:block; margin:0 auto; image-rendering:crisp-edges; image-rendering:pixelated; }
    .qr-fallback { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:bold; }

    .asset-tag { width:100%; height:0.30in; line-height:0.30in; font-size:26px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; text-align:center; display:flex; align-items:center; justify-content:center; }

    .barcode-box { width:100%; margin-top:4px; display:flex; align-items:center; justify-content:center; overflow:hidden; max-height:1.05in; }
    .barcode-inner-fixed { width:0.95in; margin:0 auto; display:flex; align-items:center; justify-content:center; }
    .barcode-wrapper { display:inline-block; max-width:100%; text-align:center; }
    .barcode-wrapper br { display:none; }
    .barcode-wrapper > div { /* outer container from library */ display:inline-block; }
    /* Ensure any barcode output (img, svg, table) scales to the fixed width */
    .barcode-box img, .barcode-box svg { max-width:100%; height:auto; display:block; margin:0 auto; }
    .barcode-box table { margin:0 auto !important; border-collapse:collapse; width:100% !important; }
    .barcode-box table td { padding:0 !important; }
    /* If library outputs nested div bars, constrain */
    .barcode-inner-fixed > div { max-width:100%; }
    .barcode-fallback { font-size:10px; }
    </style>
</head>
<body>
    @foreach($qrCodeData as $item)
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
    @endforeach
</body>
</html>
