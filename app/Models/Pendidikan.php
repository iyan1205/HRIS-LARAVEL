<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'institusi',
        'tahun_lulus',
        'pendidikan_terakhir',
        'nomer_ijazah',
        'nomer_str',
        'exp_str',
        'profesi',
        'cert_profesi',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
