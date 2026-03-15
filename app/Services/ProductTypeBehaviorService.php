<?php

namespace App\Services;

use App\Models\Producttype;

class ProductTypeBehaviorService
{
    public const CLASS_FIXED = 'FIXED';
    public const CLASS_CONSUMABLE = 'CONSUMABLE';

    public function isConsumableType(?Producttype $type): bool
    {
        if (!$type) {
            return false;
        }

        return strtoupper((string) $type->asset_class) === self::CLASS_CONSUMABLE;
    }

    public function resolveAssetClass(?Producttype $type): string
    {
        if ($this->isConsumableType($type)) {
            return self::CLASS_CONSUMABLE;
        }

        return self::CLASS_FIXED;
    }

    public function resolvePrefix(?Producttype $type, string $default = 'XX'): string
    {
        if (!$type) {
            return strtoupper($default);
        }

        $visited = [];
        $current = $type;

        while ($current) {
            if (isset($visited[$current->id])) {
                break;
            }

            $visited[$current->id] = true;
            $prefix = strtoupper(trim((string) ($current->prefix ?? '')));
            if ($prefix !== '') {
                return $prefix;
            }

            if (!$current->relationLoaded('parent')) {
                $current->load('parent');
            }
            $current = $current->parent;
        }

        return strtoupper($default);
    }
}
