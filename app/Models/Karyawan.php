<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'departemen_id',
        'unit_id',
        'jabatan_id',
        'name', //namalengkap
        'nik',
        'status_karyawan', //kontrak_atau_tetap
        'status', //active atau resign
        'tgl_resign',
        'resign_id', //alasanresign
        'nomer_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'gender',
        'alamat_ktp',
        'status_ktp',
        'telepon',
        'npwp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function pendidikan()
    {
        return $this->hasOne(Pendidikan::class, 'karyawan_id');
    }


    public function tambahPendidikan($data)
    {
        return $this->pendidikan()->create($data);
    }

    public function resignreason()
    {
        return $this->belongsTo(ResignReason::class);
    }

    public function pelatihans()
    {
        return $this->belongsToMany(Pelatihan::class)
                    ->withPivot('tanggal_expired', 'file')
                    ->withTimestamps();
    }

    public static function countByStatus($status)
    {
        return self::where('status', $status)->count();
    }

    public function kontrak()
    {
        return $this->hasMany(KontrakKaryawan::class);
    }

    public function mobilitas()
    {
        return $this->hasOne(Mobilitas::class, 'karyawan_id');
    }
    
}
