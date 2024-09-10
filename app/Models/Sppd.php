<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sppd extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'kategori_dinas',
        'fasilitas_kendaraan',
        'fasilitas_transportasi',
        'fasilitas_akomodasi',
        'cost',
        'kota_tujuan',
        'negara_tujuan',
        'rencana_kegiatan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'status',
        'approver_id',
        'updated_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
