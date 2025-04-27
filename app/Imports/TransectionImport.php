<?php

namespace App\Imports;

use App\Models\Employee;
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

            $stock = Stock::where('service_tag', $row['service_tag'])->first();
            $employee = Employee::where('emply_id', $row['employee_id'])->first();
            if ($stock && $employee) {
                Transection::create([
                    'stock_id'      => $stock->id,
                    'employee_id'   => $employee->id,
                    'issued_date'   => $row['issued_date'],
                    'quantity'      => $row['quantity'],
                ]);

                Stock::findOrFail($stock->id)->update(['is_assigned' => 1]);
            }

        }
    }
}



