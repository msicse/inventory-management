<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transection extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id', 'employee_id', 'issued_date', 'quantity'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
