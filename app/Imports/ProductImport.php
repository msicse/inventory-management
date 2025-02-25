<?php

namespace App\Imports;

use App\Models\Product;
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

            Product::create([
                'producttype_id'=> $row['producttype_id'],
                'title'         => $row['title'],
                'brand'         => $row['brand'],
                'slug'          => $row['slug'],
                'model'         => $row['model'],
                'unit'          => $row['unit'],
                'is_serial'     => $row['is_serial'],
                'is_license'    => $row['is_license'],
                'description'   => $row['description'],
            ]);

        }
    }
}



