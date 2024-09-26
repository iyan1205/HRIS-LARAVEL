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
        'biaya_transfortasi',
        'biaya_akomodasi',
        'biaya_pendaftaran',
        'biaya_uangsaku',
        'lokasi_tujuan',
        'rencana_kegiatan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'status',
        'approver_id',
        'level_approve',
        'alasan_reject',
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

     public function approve($updatedBy)
    {
        $this->updated_by =  $updatedBy; // Mengatur updated_by dengan ID pengguna
        $this->save();
    }

    public function reject($updatedBy)
    {
        $this->status = 'rejected';
        $this->updated_by =  $updatedBy; // Mengatur updated_by dengan ID pengguna
        $this->save();
    }
}
