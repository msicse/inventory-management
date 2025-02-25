<?php

namespace App\Imports;

use App\Models\Stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockAllImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            Stock::create([
                'product_id'        => $row['product_id'],
                'purchase_id'       => $row['purchase_id'],
                'producttype_id'    => $row['producttype_id'],
                'pproduct_id'       => $row['pproduct_id'],
                'asset_tag'         => $row['asset_tag'],
                'serial_no'         => $row['serial_no'],
                'service_tag'       => $row['service_tag'],
                'mac'               => $row['mac'],
                'warranty'          => $row['warranty'],
                'purchase_date'     => $row['purchase_date'],
                'expired_date'      => $row['expired_date'],
                'assigned'          => $row['assigned'],
                'is_assigned'       => $row['is_assigned'],
                'status_id'         => $row['status_id'],
                'store_id'          => $row['store_id'],
                'asset_condition'   => $row['asset_condition'],
            ]);
        }
    }
}

