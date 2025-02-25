<?php

namespace App\Imports;

use App\Models\PurchaseProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseProductImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            PurchaseProduct::create([
                'product_id'    => $row['product_id'],
                'supplier_id'   => $row['supplier_id'],
                'purchase_id'   => $row['purchase_id'],
                'quantity'      => $row['quantity'],
                'unit_price'    => $row['unit_price'],
                'total_price'   => $row['total_price'],
                'serials'       => $row['serials'],
                'warranty'      => $row['warranty'],
                'purchase_date' => $row['purchase_date'],
                'expired_date'  => $row['expired_date'],
                'is_stocked'    => $row['is_stocked'],
            ]);



        }
    }
}



