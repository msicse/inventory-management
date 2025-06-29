<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Employee;
use App\Models\Transection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransectionImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public static $errors = [];
    public function collection(Collection $rows)
    {

        foreach ($rows as $index => $row) {
            try {
                $stock = Stock::where('service_tag', $row['serial_no'])->first();
                $employee = Employee::where('emply_id', $row['employee_id'])->first();

                if ($stock && $employee) {
                    $issuedDate = !empty($row['issued_date'])
                    ? Carbon::parse($row['issued_date'])->format('Y-m-d')
                    : Carbon::today()->format('Y-m-d');
                    Transection::create([
                        'stock_id' => $stock->id,
                        'employee_id' => $employee->id,
                        'issued_date' => $issuedDate,
                        'quantity' => $row['quantity'] ?? 1,
                    ]);
                    $stock->update(['is_assigned' => 1]);
                } else {
                    // Log or handle missing stock/employee
                    Log::warning("Row $index: Stock or Employee not found.", ['row' => $row]);
                    self::$errors[] = "Row $index: Stock or Employee not found.";
                }
            } catch (\Exception $e) {
                // Log the error for this row
                Log::error("Row $index: Import error - " . $e->getMessage(), ['row' => $row]);
                self::$errors[] = "Row $index: Import error - " . $e->getMessage();
            }

        }
    }
}



