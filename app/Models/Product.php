<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'producttype_id',
        'title',
        'brand',
        'slug',
        'model',
        'unit',
        'is_serial',
        'is_license',
        'description'
    ];

    public function type(){
        return $this->belongsTo('App\Models\Producttype','producttype_id');
    }

}
