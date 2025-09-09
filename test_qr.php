<?php

/**
 * Quick test script for Purchase QR Code generation
 * Run with: php artisan tinker test_qr.php
 */

use App\Models\Purchase;
use App\Models\Stock;
use App\Services\QrCodeService;

// Test purchase ID (should be approved)
$purchaseId = 1;

echo "Testing QR Code generation for Purchase ID: $purchaseId\n";

try {
    // Get purchase
    $purchase = Purchase::findOrFail($purchaseId);
    echo "Purchase found: " . $purchase->invoice_no . "\n";

    // Check if purchase is approved
    if ($purchase->is_stocked != 1) {
        echo "ERROR: Purchase is not approved (is_stocked = " . $purchase->is_stocked . ")\n";
        exit(1);
    }

    echo "Purchase is approved ✓\n";

    // Get stocks from this purchase
    $stocks = Stock::where('purchase_id', $purchaseId)->get();
    echo "Found " . $stocks->count() . " stock items\n";

    if ($stocks->isEmpty()) {
        echo "ERROR: No stock items found for this purchase\n";
        exit(1);
    }

    // Test QR code generation for first stock
    $qrCodeService = app(QrCodeService::class);
    $firstStock = $stocks->first();
    echo "Testing QR generation for stock ID: " . $firstStock->id . "\n";

    // Generate QR data
    $qrData = $qrCodeService->createSimpleStockQrData($firstStock);
    echo "QR Data generated:\n" . $qrData . "\n";

    // Test QR code generation
    $qrCode = $qrCodeService->generateSimpleStockQrCode($firstStock, 'svg', 200);
    echo "QR Code SVG generated successfully ✓\n";

    echo "\nTest completed successfully! ✅\n";
    echo "You can now test the full URL: /purchase/$purchaseId/print-qrcodes\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
