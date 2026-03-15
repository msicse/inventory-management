<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'employee_id',
        'transection_id',
        'issue_movement_id',
        'movement_type',
        'qty',
        'movement_date',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'integer',
        'movement_date' => 'date',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function transection()
    {
        return $this->belongsTo(Transection::class, 'transection_id');
    }

    public function issueMovement()
    {
        return $this->belongsTo(self::class, 'issue_movement_id');
    }

    public function returns()
    {
        return $this->hasMany(self::class, 'issue_movement_id');
    }
}
