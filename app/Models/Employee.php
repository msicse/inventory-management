<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ["department_id", "emply_id", "name", "designation", "phone", "email", "status", "date_of_join", "type"];
    /**
     * Get the user that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function transections()
    {
        return $this->hasMany(Transection::class, 'employee_id');
    }

    /**
     * Get the user account linked to this employee.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'emply_id');
    }
}
