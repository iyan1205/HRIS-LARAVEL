<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromosiJabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'jabatan_sekarang',
        'jabatan_promosi',
        'tanggal_promosi',
    ];

    public function karyawan(){
        return $this->hasOne(Karyawan::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
