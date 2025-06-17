<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Producttype;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $producttype = Producttype::where("slug", trim(Str::slug($row['product_type'])))->first();
            $title =  $row['brand']. " " .  $row['brand'] . " " . $producttype->name;



            Product::create([
                'producttype_id'=> $producttype->id,
                'title'         => $title,
                'brand'         => $row['brand'],
                'slug'          => Str::slug($title),
                'model'         => $row['model'],
                'unit'          => $row['unit'],
                'is_serial'     => $row['is_serial'] == 1 ? 1 : 2, // Assuming 1 is true and 2 is false
                'is_license'    => $row['is_license'] == 1 ? 1 : 2, // Assuming 1 is true and 2 is false
                'description'   => $row['description'],
            ]);

        }
    }
}



