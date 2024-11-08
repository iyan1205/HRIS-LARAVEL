<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tanggal_expired',
        'file'
    ];

    public function karyawans()
    {
        return $this->belongsToMany(Karyawan::class)
                    ->withPivot('tanggal_expired', 'file')
                    ->withTimestamps();
    }
}
