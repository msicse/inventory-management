<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producttype extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'prefix',
        'asset_class',
        'description',
        'slug',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'prefix' => 'string',
        'asset_class' => 'string',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'producttype_id');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'producttype_id');
    }

    public function parent()
    {
        return $this->belongsTo(Producttype::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Producttype::class, 'parent_id');
    }

    public function getFullPathAttribute(): string
    {
        $parts = [];
        $visited = [];
        $current = $this;

        while ($current) {
            if (isset($visited[$current->id])) {
                break;
            }

            $visited[$current->id] = true;
            array_unshift($parts, (string) $current->name);

            if (!$current->relationLoaded('parent')) {
                $current->load('parent');
            }
            $current = $current->parent;
        }

        return implode(' > ', $parts);
    }


}
