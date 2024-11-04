<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'manager_id',
        'level_approve',
        'file_upload',
        'total_days',
        'alasan_reject',
        'updated_by',
        'updated_by_atasan',
        'updated_at_atasan'

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updated_by() {
        return $this->belongsToMany(User::class);
    }

    public function LeaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approve($updatedBy)
    {
        $this->updated_by =  $updatedBy; // Mengatur updated_by dengan ID pengguna
        $this->save();
    }

    public function cancel($updatedBy)
    {
        $this->status = 'canceled';
        $this->updated_by =  $updatedBy; // Mengatur updated_by dengan ID pengguna
        $this->save();
    }

    public function reject($updatedBy)
    {
        $this->status = 'rejected';
        $this->updated_by =  $updatedBy; // Mengatur updated_by dengan ID pengguna
        $this->save();
    }

}
