<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Stock;
use Exception;

class QrCodeService
{
    /**
     * Generate QR code for stock item
     *
     * @param Stock $stock
     * @param string $format (svg, png, eps, pdf)
     * @param int $size
     * @return string
     */
    public function generateStockQrCode(Stock $stock, $format = 'svg', $size = 200)
    {
        try {
            $qrData = $this->createStockQrData($stock);

            return QrCode::size($size)
                        ->format($format)
                        ->generate($qrData);
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR code as base64 encoded image
     *
     * @param Stock $stock
     * @param string $format
     * @param int $size
     * @return string
     */
    public function generateStockQrCodeBase64(Stock $stock, $format = 'png', $size = 200)
    {
        try {
            $qrData = $this->createStockQrData($stock);

            $qrCode = QrCode::size($size)
                           ->format($format)
                           ->generate($qrData);

            return 'data:image/' . $format . ';base64,' . base64_encode($qrCode);
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR code and save to file
     *
     * @param Stock $stock
     * @param string $filename
     * @param string $format
     * @param int $size
     * @return string File path
     */
    public function generateAndSaveStockQrCode(Stock $stock, $filename = null, $format = 'png', $size = 200)
    {
        try {
            $qrData = $this->createStockQrData($stock);

            if (!$filename) {
                $filename = 'qr_stock_' . $stock->id . '_' . time() . '.' . $format;
            }

            $qrCode = QrCode::size($size)
                           ->format($format)
                           ->generate($qrData);

            $path = 'qrcodes/' . $filename;
            Storage::disk('public')->put($path, $qrCode);

            return $path;
        } catch (Exception $e) {
            throw new Exception('Failed to generate and save QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate custom QR code with any data
     *
     * @param string $data
     * @param string $format
     * @param int $size
     * @param array $options
     * @return string
     */
    public function generateCustomQrCode($data, $format = 'svg', $size = 200, $options = [])
    {
        try {
            // Force SVG format if imagick is not available (prevents PNG errors)
            if ($format !== 'svg' && !extension_loaded('imagick')) {
                $format = 'svg';
            }

            $qrCode = QrCode::size($size)->format($format);

            // Apply options
            if (isset($options['margin'])) {
                $qrCode->margin($options['margin']);
            }

            if (isset($options['color'])) {
                $qrCode->color($options['color']['r'], $options['color']['g'], $options['color']['b']);
            }

            if (isset($options['backgroundColor'])) {
                $bg = $options['backgroundColor'];
                $qrCode->backgroundColor($bg['r'], $bg['g'], $bg['b']);
            }

            if (isset($options['errorCorrection'])) {
                $qrCode->errorCorrection($options['errorCorrection']);
            }

            return $qrCode->generate($data);
        } catch (Exception $e) {
            throw new Exception('Failed to generate custom QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate URL QR code
     *
     * @param string $url
     * @param string $format
     * @param int $size
     * @return string
     */
    public function generateUrlQrCode($url, $format = 'svg', $size = 200)
    {
        return $this->generateCustomQrCode($url, $format, $size);
    }

    /**
     * Generate WiFi QR code
     *
     * @param string $ssid
     * @param string $password
     * @param string $security (WEP, WPA, nopass)
     * @param string $format
     * @param int $size
     * @return string
     */
    public function generateWifiQrCode($ssid, $password = '', $security = 'WPA', $format = 'svg', $size = 200)
    {
        $wifiString = "WIFI:T:{$security};S:{$ssid};P:{$password};;";
        return $this->generateCustomQrCode($wifiString, $format, $size);
    }

    /**
     * Generate contact (vCard) QR code
     *
     * @param array $contact
     * @param string $format
     * @param int $size
     * @return string
     */
    public function generateContactQrCode($contact, $format = 'svg', $size = 200)
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:" . ($contact['name'] ?? '') . "\n";
        $vcard .= "ORG:" . ($contact['organization'] ?? '') . "\n";
        $vcard .= "TEL:" . ($contact['phone'] ?? '') . "\n";
        $vcard .= "EMAIL:" . ($contact['email'] ?? '') . "\n";
        $vcard .= "URL:" . ($contact['website'] ?? '') . "\n";
        $vcard .= "END:VCARD";

        return $this->generateCustomQrCode($vcard, $format, $size);
    }

    /**
     * Create QR data for stock item
     *
     * @param Stock $stock
     * @return string
     */
    public function createStockQrData(Stock $stock)
    {
        // Create JSON data for stock
        $qrData = [
            'type' => 'inventory_item',
            'stock_id' => $stock->id,
            'asset_tag' => $stock->asset_tag,
            'serial_number' => $stock->service_tag,
            'url' => url('/admin/stock/' . $stock->id . '/details'),
            'organization' => 'RMG Sustainability Council',
            'timestamp' => now()->toISOString()
        ];

        // Add product information if available
        try {
            if ($stock->product) {
                $qrData['product_name'] = $stock->product->name;
                if ($stock->product->type) {
                    $qrData['product_type'] = $stock->product->type->type;
                }
            }
        } catch (Exception $e) {
            // Skip product info if relationship fails
        }

        return json_encode($qrData);
    }

    /**
     * Create simple text QR data for stock item (for scanner compatibility)
     *
     * @param Stock $stock
     * @return string
     */
    public function createSimpleStockQrData(Stock $stock)
    {
        $qrText = "RMG Sustainability Council\n";
        $qrText .= "S/N: " . ($stock->service_tag ?: 'N/A') . "\n";

        // Add product type and model if available
        try {
            if ($stock->product) {
                if ($stock->product->type) {
                    $qrText .= "Type: " . $stock->product->type->type . "\n";
                }
                $qrText .= "Model: " . $stock->product->name . "\n";
            }
        } catch (Exception $e) {
            $qrText .= "Type: N/A\n";
            $qrText .= "Model: N/A\n";
        }

        $qrText .= "Asset Tag: " . $stock->asset_tag . "\n";
        $qrText .= "URL: its.rsc-bd.org";

        return $qrText;
    }

    /**
     * Generate simple text QR code for stock
     *
     * @param Stock $stock
     * @param string $format
     * @param int $size
     * @return string
     */
    public function generateSimpleStockQrCode(Stock $stock, $format = 'svg', $size = 200)
    {
        $qrData = $this->createSimpleStockQrData($stock);
        return $this->generateCustomQrCode($qrData, $format, $size);
    }
}
