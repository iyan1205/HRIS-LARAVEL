<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BenefitKartap extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nominal',
        'jenis_benefit',
        'form_pengajuan',
        'resume',
        'bukti_pembayaran',
        'status',
        'alasan_reject',
        'approval_1_by',
        'approval_2_by',
        'approved_by',
        'approval_1_at',
        'approval_2_at',
        'approved_at'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'approval_1_at' => 'datetime',
        'approval_2_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $hidden = [
        'timestamps',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approval1By()
    {
        return $this->belongsTo(User::class, 'approval_1_by');
    }

    public function approval2By()
    {
        return $this->belongsTo(User::class, 'approval_2_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}

