<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeApprovalHistory extends Model
{
    protected $fillable = [
        'overtime_id',
        'user_id',
        'action',
        'level_approve',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }
}
