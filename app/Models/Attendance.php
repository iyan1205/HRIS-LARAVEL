<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jam_masuk',
        'foto_jam_masuk',
        'jam_keluar',
        'foto_jam_keluar',
        'device_info',
        'ip_address',
        'status', // hadir, pulang
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalDurationAttribute()
    {
        // Jika tidak punya jam keluar → belum absen pulang
        if (!$this->jam_keluar || $this->jam_keluar == $this->jam_masuk) {
            return "Belum absen pulang";
        }

        // Gabungkan created_at + jam_masuk
        $tanggalMasuk = $this->created_at->format('Y-m-d') . ' ' . $this->jam_masuk;
        $datetimeMasuk = Carbon::parse($tanggalMasuk);

        // Gabungkan updated_at + jam_keluar
        $tanggalPulang = $this->updated_at->format('Y-m-d') . ' ' . $this->jam_keluar;
        $datetimePulang = Carbon::parse($tanggalPulang);

        // Jika waktu pulang <= waktu masuk → definisikan sebagai belum absen pulang
        if ($datetimePulang->lessThanOrEqualTo($datetimeMasuk)) {
            return "Belum absen pulang";
        }

        // Hitung durasi
        $durationInMinutes = $datetimeMasuk->diffInMinutes($datetimePulang);
        if ($durationInMinutes > 1440) {
            return "Tidak absen pulang";
        }
        $hours = intdiv($durationInMinutes, 60);
        $minutes = $durationInMinutes % 60;
        $seconds = $datetimeMasuk->diffInSeconds($datetimePulang) % 60;

        return sprintf('%d Jam %d Menit %d Detik', $hours, $minutes, $seconds);
    }

}

