<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'total_price',
        'invoice_no',
        'reference_invoice',
        'challan_no',
        'purchase_date',
        'received_date',
        'is_stocked',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'received_date' => 'datetime',
    ];

    /**
     * Get the purchase products for this purchase.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
