<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OncallApprovalHistory extends Model
{
    protected $fillable = [
        'oncall_id',
        'user_id',
        'action',
        'level_approve',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oncall()
    {
        return $this->belongsTo(OnCall::class);
    }
}
