<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobilitas extends Model
{
    use HasFactory;

    protected $table = 'mobilitas_jabatan';

    protected $fillable = [
        'karyawan_id',
        'aspek',
        'jabatan_sekarang',
        'jabatan_baru',
        'departemen_sekarang',
        'departemen_baru',
        'unit_sekarang',
        'unit_baru',
        'tanggal_efektif'
        
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

}
