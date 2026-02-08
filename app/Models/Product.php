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
        'is_taggable',
        'is_consumable',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_serial' => 'boolean',
        'is_license' => 'boolean',
        'is_taggable' => 'boolean',
        'is_consumable' => 'boolean',
    ];

    public function type(){
        return $this->belongsTo(Producttype::class, 'producttype_id');
    }

}
