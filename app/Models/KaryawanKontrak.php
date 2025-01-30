<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKontrak extends Model
{
    use HasFactory;

    protected $table = 'karyawan_kontrak';
    protected $primaryKey = 'id_kontrak';

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
