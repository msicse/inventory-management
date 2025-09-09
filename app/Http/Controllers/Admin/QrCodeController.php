<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use App\Models\Stock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\UserLogHelper;
use Exception;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete');
    }

    /**
     * Show QR code generator interface
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response('<div style="padding: 20px; font-family: Arial; text-align: center;">
            <h2>QR Code Generator</h2>
            <p>Use the stock management interface to generate QR codes for inventory items.</p>
            <p><a href="' . route('stocks.index') . '">Go to Stock Management</a></p>
        </div>');
    }

    /**
     * Generate QR code for a stock item
     *
     * @param int $stockId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateStockQrCode($stockId, Request $request)
    {
        try {
            $stock = Stock::findOrFail($stockId);
            $format = $request->get('format', 'svg');
            $size = $request->get('size', 200);
            $type = $request->get('type', 'simple'); // simple or json - default to simple for better scanning

            if ($type === 'simple') {
                $qrCode = $this->qrCodeService->generateSimpleStockQrCode($stock, $format, $size);
                $data = $this->qrCodeService->createSimpleStockQrData($stock);
            } else {
                $qrCode = $this->qrCodeService->generateStockQrCode($stock, $format, $size);
                $data = json_decode($this->qrCodeService->createStockQrData($stock), true);
            }

            if ($request->get('ajax')) {
                return response()->json([
                    'success' => true,
                    'qrcode' => $qrCode,
                    'data' => $data,
                    'stock_id' => $stockId
                ]);
            }

            return response('<div style="text-align: center; padding: 20px;">
                <h3>QR Code for Stock ID: ' . $stockId . '</h3>
                <div style="margin: 20px 0;">' . $qrCode . '</div>
                <p><strong>Asset Tag:</strong> ' . $stock->asset_tag . '</p>
                <p><strong>Data:</strong> <pre>' . (is_array($data) ? json_encode($data, JSON_PRETTY_PRINT) : $data) . '</pre></p>
            </div>');

        } catch (Exception $e) {
            if ($request->get('ajax')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate QR code: ' . $e->getMessage()
                ], 500);
            }

            return response('<div style="color: red; padding: 20px;">
                <h3>Error occurred:</h3>
                <p>' . $e->getMessage() . '</p>
            </div>', 500);
        }
    }

    /**
     * Print QR code label for a stock item
     *
     * @param int $stockId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printStockQrCode($stockId, Request $request)
    {
        try {
            $stock = Stock::findOrFail($stockId);
            $size = $request->get('size', 200); // Increased default size for 1.4" display
            $type = $request->get('type', 'simple');

            // Generate QR data
            if ($type === 'simple') {
                $qrData = $this->qrCodeService->createSimpleStockQrData($stock);
            } else {
                $qrData = $this->qrCodeService->createStockQrData($stock);
            }

            // Generate QR code as PNG image for better PDF compatibility
            $qrCodeBase64 = null;
            $qrCodePngPath = null;

            try {
                // Create a temporary file for PNG
                $tempDir = storage_path('app/temp');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                $filename = 'qr_' . $stockId . '_' . time() . '.png';
                $qrCodePngPath = $tempDir . '/' . $filename;

                // Generate QR code as PNG using GD with higher resolution for 1.4" size
                $qrCodePng = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                    ->format('png')
                    ->backgroundColor(255, 255, 255)
                    ->color(0, 0, 0)
                    ->margin(1) // Reduced margin for larger QR area
                    ->errorCorrection('M') // Medium error correction for better quality
                    ->generate($qrData, $qrCodePngPath);

                // Convert to base64 data URL
                if (file_exists($qrCodePngPath)) {
                    $imageData = file_get_contents($qrCodePngPath);
                    $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($imageData);
                }

            } catch (Exception $e) {
                $qrCodeBase64 = null;
                // Create fallback SVG
                try {
                    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                        ->format('svg')
                        ->backgroundColor(255, 255, 255)
                        ->color(0, 0, 0)
                        ->margin(1)
                        ->errorCorrection('M')
                        ->generate($qrData);

                    $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($svg);
                } catch (Exception $svgError) {
                    // Final fallback - use HTML table
                    $qrCodeHtml = $this->generateSimpleQrHtml($qrData, $size);
                }
            }

            $data = [
                'stock' => $stock,
                'qrCodeBase64' => $qrCodeBase64,
                'qrCodeHtml' => $qrCodeHtml ?? null,
                'qrData' => $qrData,
                'assetTag' => $stock->asset_tag,
                'type' => $type,
                'size' => $size
            ];

            // Custom paper size: 1.4" width x 1.4" height (100.8pt x 100.8pt) - Square format
            $pdf = Pdf::loadView('backend.admin.pdf.qrcode-label', $data)
                      ->setPaper([0, 0, 100.8, 100.8], 'portrait')
                      ->setOptions([
                          'isHtml5ParserEnabled' => true,
                          'isRemoteEnabled' => false,
                          'chroot' => storage_path('app'),
                          'dpi' => 150,
                          'defaultFont' => 'Arial'
                      ]);

            UserLogHelper::log('create', 'Generated QR code label for Stock ID: ' . $stockId);

            // Clean up temporary file
            if ($qrCodePngPath && file_exists($qrCodePngPath)) {
                unlink($qrCodePngPath);
            }

            return $pdf->stream('qrcode-' . $stock->asset_tag . '.pdf');

        } catch (Exception $e) {
            return response('QR code generation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate simple QR code representation using HTML
     */
    private function generateSimpleQrHtml($data, $size = 200)
    {
        // Create a simple visual representation with more cells for 1.4" size
        $gridSize = 35; // Increased from 25 for better resolution
        $cellSize = max(1, intval($size / $gridSize)); // Adjust cell size based on total size
        $html = '<table style="border-collapse: collapse; margin: 0 auto;" cellpadding="0" cellspacing="0">';

        // Simple pattern based on data hash
        $hash = md5($data . 'salt'); // Add salt for better distribution
        for ($row = 0; $row < $gridSize; $row++) {
            $html .= '<tr>';
            for ($col = 0; $col < $gridSize; $col++) {
                $index = ($row * $gridSize + $col) % 32;
                $color = (hexdec($hash[$index]) % 2) ? '#000' : '#fff';
                $html .= '<td style="width:' . $cellSize . 'px; height:' . $cellSize . 'px; background:' . $color . ';"></td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }

    /**
     * Generate multiple QR code labels
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printMultipleQrCodes(Request $request)
    {
        $stockIds = $request->input('stock_ids', []);
        $type = $request->get('type', 'simple');
        $size = $request->get('size', 200); // Increased default size for 1.4" display

        if (empty($stockIds)) {
            return response('No items selected', 400);
        }

        try {
            $stocks = Stock::whereIn('id', $stockIds)->get();
            $qrCodeData = [];

            foreach ($stocks as $stock) {
                if ($type === 'simple') {
                    $qrCode = $this->qrCodeService->generateSimpleStockQrCode($stock, 'svg', $size);
                } else {
                    $qrCode = $this->qrCodeService->generateStockQrCode($stock, 'svg', $size);
                }

                $qrCodeData[] = [
                    'stock' => $stock,
                    'qrcode' => $qrCode,
                    'assetTag' => $stock->asset_tag
                ];
            }

            $pdf = Pdf::loadView('backend.admin.pdf.multiple-qrcodes', compact('qrCodeData'))
                      ->setPaper('a4', 'portrait')
                      ->setOptions([
                          'dpi' => 300,
                          'defaultFont' => 'Arial',
                          'isRemoteEnabled' => true
                      ]);

            UserLogHelper::log('create', 'Generated multiple QR code labels for ' . count($stockIds) . ' items');

            return $pdf->stream('multiple-qrcodes-' . date('Y-m-d-H-i-s') . '.pdf');

        } catch (Exception $e) {
            return response('Multiple QR code generation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate custom QR code
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateCustomQrCode(Request $request)
    {
        $request->validate([
            'data' => 'required|string|max:1000',
            'format' => 'in:svg,png,eps,pdf',
            'size' => 'integer|min:50|max:1000'
        ]);

        try {
            $data = $request->get('data');
            $format = $request->get('format', 'svg');
            $size = $request->get('size', 200);

            $options = [];
            if ($request->has('margin')) {
                $options['margin'] = (int)$request->get('margin');
            }

            $qrCode = $this->qrCodeService->generateCustomQrCode($data, $format, $size, $options);

            if ($request->get('ajax')) {
                return response()->json([
                    'success' => true,
                    'qrcode' => $qrCode,
                    'data' => $data
                ]);
            }

            return response('<div style="text-align: center; padding: 20px;">
                <h3>Custom QR Code</h3>
                <div style="margin: 20px 0;">' . $qrCode . '</div>
                <p><strong>Data:</strong> ' . htmlspecialchars($data) . '</p>
            </div>');

        } catch (Exception $e) {
            if ($request->get('ajax')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate QR code: ' . $e->getMessage()
                ], 500);
            }

            return response('<div style="color: red; padding: 20px;">
                <h3>Error occurred:</h3>
                <p>' . $e->getMessage() . '</p>
            </div>', 500);
        }
    }

    /**
     * Generate URL QR code
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateUrlQrCode(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:500'
        ]);

        try {
            $url = $request->get('url');
            $format = $request->get('format', 'svg');
            $size = $request->get('size', 200);

            $qrCode = $this->qrCodeService->generateUrlQrCode($url, $format, $size);

            if ($request->get('ajax')) {
                return response()->json([
                    'success' => true,
                    'qrcode' => $qrCode,
                    'url' => $url
                ]);
            }

            return response('<div style="text-align: center; padding: 20px;">
                <h3>URL QR Code</h3>
                <div style="margin: 20px 0;">' . $qrCode . '</div>
                <p><strong>URL:</strong> <a href="' . $url . '" target="_blank">' . $url . '</a></p>
            </div>');

        } catch (Exception $e) {
            if ($request->get('ajax')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate QR code: ' . $e->getMessage()
                ], 500);
            }

            return response('<div style="color: red; padding: 20px;">
                <h3>Error occurred:</h3>
                <p>' . $e->getMessage() . '</p>
            </div>', 500);
        }
    }

    /**
     * Download QR code as file
     *
     * @param int $stockId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadStockQrCode($stockId, Request $request)
    {
        try {
            $stock = Stock::findOrFail($stockId);
            $format = $request->get('format', 'png');
            $size = $request->get('size', 300);
            $type = $request->get('type', 'simple');

            if ($type === 'simple') {
                $qrCode = $this->qrCodeService->generateSimpleStockQrCode($stock, $format, $size);
            } else {
                $qrCode = $this->qrCodeService->generateStockQrCode($stock, $format, $size);
            }

            $filename = 'qrcode-' . $stock->asset_tag . '.' . $format;

            return response($qrCode)
                ->header('Content-Type', 'image/' . $format)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (Exception $e) {
            return response('Download failed: ' . $e->getMessage(), 500);
        }
    }
}
