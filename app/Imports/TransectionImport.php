<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\Transection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransectionImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            Transection::create([
                'stock_id'      => $row['stock_id'],
                'employee_id'   => $row['employee_id'],
                'issued_date'   => $row['issued_date'],
                'quantity'      => $row['quantity'],
            ]);

            Stock::findOrFail($row['stock_id'])->update(['is_assigned' => 1]);



        }
    }
}



