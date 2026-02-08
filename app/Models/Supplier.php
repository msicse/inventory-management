<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'phone',
        'email',
        'address',
        'description',
    ];

    /**
     * Get all purchases for this supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}
