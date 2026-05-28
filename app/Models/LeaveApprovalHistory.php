<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApprovalHistory extends Model
{
    protected $fillable = [
        'leave_application_id',
        'user_id',
        'action',
        'level_approve',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveApplication()
    {
        return $this->belongsTo(LeaveApplication::class);
    }
}
