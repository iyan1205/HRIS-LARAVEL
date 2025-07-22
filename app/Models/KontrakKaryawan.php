<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakKaryawan extends Model
{
    use HasFactory;

    protected $table = 'kontrak_karyawan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'karyawan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi_kontrak',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

}
