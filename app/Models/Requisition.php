<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(Producttype::class, 'producttype_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
