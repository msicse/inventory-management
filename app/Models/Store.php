<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    function stocks()
    {
        return $this->hasMany(Stock::class, "store_id");
    }
}
