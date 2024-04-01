<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'max_amount',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function LeaveApplication()
    {
        return $this->hasOne(LeaveApplication::class, 'leave_type_id');
    }

}
