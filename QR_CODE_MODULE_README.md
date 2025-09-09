# QR Code Module Documentation

## Overview
This QR Code module provides comprehensive QR code generation functionality for the inventory management system. It includes both stock-specific QR codes and general-purpose QR code generation.

## Features

### 1. Stock QR Codes
- Generate QR codes for inventory items
- Two formats: JSON (detailed) and Simple (text-based)
- Print individual QR code labels (3.5" x 1.4")
- Bulk print multiple QR code labels
- Download QR codes as image files

### 2. Custom QR Codes
- URL QR codes
- Plain text QR codes
- WiFi network QR codes
- Contact (vCard) QR codes
- Customizable size, format, and styling

### 3. Integration
- Seamlessly integrated with existing inventory interface
- QR code buttons alongside barcode buttons
- Bulk selection for QR code printing
- Dedicated QR code generator interface

## Installation

### Dependencies
The module uses the `simplesoftwareio/simple-qrcode` package which is automatically installed.

### Files Created
- `app/Services/QrCodeService.php` - Core QR code generation service
- `app/Http/Controllers/Admin/QrCodeController.php` - QR code controller
- `resources/views/backend/admin/pdf/qrcode-label.blade.php` - Single QR label template
- `resources/views/backend/admin/pdf/multiple-qrcodes.blade.php` - Multiple QR labels template
- `resources/views/backend/admin/qrcode/index.blade.php` - QR code generator interface

### Routes Added
```php
// Stock QR Code routes
Route::get('stock/{id}/qrcode', 'QrCodeController@generateStockQrCode');
Route::get('stock/{id}/print-qrcode', 'QrCodeController@printStockQrCode');
Route::get('stock/{id}/download-qrcode', 'QrCodeController@downloadStockQrCode');
Route::post('stock/print-multiple-qrcodes', 'QrCodeController@printMultipleQrCodes');

// Custom QR Code routes
Route::get('qrcode-generator', 'QR Code Generator Interface');
Route::post('qrcode/custom', 'QrCodeController@generateCustomQrCode');
Route::post('qrcode/url', 'QrCodeController@generateUrlQrCode');
```

## Usage

### 1. Stock QR Codes

#### Generate QR Code for a Stock Item
```php
// In controller or service
$qrCodeService = app(QrCodeService::class);
$stock = Stock::find(1);

// Generate simple text QR code
$qrCode = $qrCodeService->generateSimpleStockQrCode($stock, 'svg', 200);

// Generate detailed JSON QR code
$qrCode = $qrCodeService->generateStockQrCode($stock, 'svg', 200);
```

#### URLs for Stock QR Codes
- View QR Code: `/admin/stock/{id}/qrcode`
- Print QR Label: `/admin/stock/{id}/print-qrcode`
- Download QR Code: `/admin/stock/{id}/download-qrcode`

#### QR Code Data Formats

**Simple Format (Text):**
```
RMG|Asset:L002030023|S/N:121wasdhsa|ID:1|URL:its.rsc-bd.org
```

**Detailed Format (JSON):**
```json
{
  "type": "inventory_item",
  "stock_id": 1,
  "asset_tag": "L002030023",
  "serial_number": "121wasdhsa",
  "product_name": "Samsung A34",
  "product_type": "Mobile",
  "url": "http://store11.test/admin/stock/1/details",
  "organization": "RMG Sustainability Council",
  "timestamp": "2025-09-09T12:00:00.000Z"
}
```

### 2. Custom QR Codes

#### Using the Service
```php
$qrCodeService = app(QrCodeService::class);

// URL QR Code
$qrCode = $qrCodeService->generateUrlQrCode('https://its.rsc-bd.org', 'svg', 200);

// WiFi QR Code
$qrCode = $qrCodeService->generateWifiQrCode('NetworkName', 'password123', 'WPA', 'svg', 200);

// Contact QR Code
$contact = [
    'name' => 'John Doe',
    'phone' => '+1234567890',
    'email' => 'john@example.com',
    'organization' => 'RMG Sustainability Council',
    'website' => 'https://rsc-bd.org'
];
$qrCode = $qrCodeService->generateContactQrCode($contact, 'svg', 200);

// Custom text QR Code
$qrCode = $qrCodeService->generateCustomQrCode('Any custom text', 'svg', 200);
```

#### Using the Web Interface
Visit `/admin/qrcode-generator` for a user-friendly interface to generate various types of QR codes.

### 3. Inventory Integration

#### Individual Actions
Each inventory item now has QR code action buttons:
- 🖨️ **Print QR Code** - Generate printable QR label
- 📱 **View QR Code** - Display QR code in browser

#### Bulk Actions
- Select multiple inventory items using checkboxes
- Click "Print Selected QR Codes" to generate bulk QR labels
- Access QR Generator from the inventory page

## API Endpoints

### Stock QR Codes
```
GET /admin/stock/{id}/qrcode?format=svg&size=200&type=simple
GET /admin/stock/{id}/print-qrcode?size=200&type=simple
GET /admin/stock/{id}/download-qrcode?format=png&size=300&type=simple
POST /admin/stock/print-multiple-qrcodes
```

### Custom QR Codes
```
POST /admin/qrcode/custom
Body: {
  "data": "text to encode",
  "format": "svg",
  "size": 200,
  "ajax": true
}

POST /admin/qrcode/url
Body: {
  "url": "https://example.com",
  "format": "svg",
  "size": 200,
  "ajax": true
}
```

## Configuration

### QR Code Options
- **Formats**: SVG (vector), PNG (raster), EPS, PDF
- **Sizes**: 50px to 1000px
- **Error Correction**: L, M, Q, H levels
- **Colors**: Custom foreground and background colors
- **Margins**: Adjustable margin settings

### Label Specifications
- **Single Label**: 3.5" × 1.4" (252pt × 100.8pt)
- **Multiple Labels**: A4 paper with 2 columns
- **QR Code Size**: Optimized for label dimensions
- **Font**: Arial, various sizes for different elements

## Security

### Permissions
QR code generation requires the same permissions as barcode generation:
- `purchase-list` - View QR codes
- `purchase-create` - Generate QR codes
- `purchase-edit` - Edit QR code settings
- `purchase-delete` - Delete QR codes (if implemented)

### Data Validation
- Input sanitization for all QR code data
- URL validation for web addresses
- Length limits for text content
- Format validation for images

## Troubleshooting

### Common Issues

1. **QR Code not generating**
   - Check if simplesoftwareio/simple-qrcode package is installed
   - Verify routes are cached: `php artisan route:clear`
   - Check file permissions in storage directory

2. **PDF generation fails**
   - Ensure DomPDF is properly configured
   - Check if temporary directory exists and is writable
   - Clear view cache: `php artisan view:clear`

3. **QR Code buttons not showing**
   - Clear browser cache
   - Check if asset_tag exists for the inventory item
   - Verify user has proper permissions

### Debug Commands
```bash
# Clear all caches
php artisan config:clear && php artisan route:clear && php artisan view:clear

# Test QR code service
php artisan tinker
app('App\Services\QrCodeService')->generateCustomQrCode('Test', 'svg', 100);

# Check routes
php artisan route:list | grep qrcode
```

## Customization

### Adding New QR Code Types
1. Add new method to `QrCodeService.php`
2. Create corresponding controller method
3. Add route and validation
4. Update frontend interface

### Modifying Label Design
Edit the Blade templates:
- `qrcode-label.blade.php` - Single label design
- `multiple-qrcodes.blade.php` - Multiple labels layout

### Custom Styling
Modify CSS in the templates to change:
- QR code positioning
- Font styles and sizes
- Border and padding
- Paper dimensions

## Future Enhancements

### Planned Features
- QR code analytics and tracking
- Batch QR code generation from CSV
- QR code expiration dates
- Dynamic QR codes with live data
- Mobile scanning app integration

### Extension Points
- Custom QR code formats
- Third-party QR code services
- Advanced error correction
- Branded QR codes with logos
- Integration with asset tracking systems

## Support

For issues or questions regarding the QR Code module:
1. Check this documentation
2. Review Laravel logs in `storage/logs/`
3. Test with simple examples
4. Verify all dependencies are installed

---

**Version**: 1.0  
**Last Updated**: September 9, 2025  
**Dependencies**: Laravel 11, simplesoftwareio/simple-qrcode, DomPDF
