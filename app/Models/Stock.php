<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        "asset_tag", "service_tag", "is_assigned", "status_id", "store_id"
    ];

    /**
     * Get the user that owns the Stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
    public function producttype()
    {
        return $this->belongsTo(Producttype::class, 'producttype_id');
    }
    public function transections()
    {
        return $this->hasMany(Transection::class, 'stock_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function status()
    {
        return $this->belongsTo(AssetStatus::class, 'status_id');
    }

    public function currentUser()
    {
        return $this->hasMany(Transection::class, 'stock_id')
            ->whereNull('return_date'); // Ensure 'returned_field' is the column tracking returned assets
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


}
