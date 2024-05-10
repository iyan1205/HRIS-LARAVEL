<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manager_id',
        'level',
        'level_approve',
    ];

    protected $hidden = [
        'remember_token',
    ];
    
    public function atasan()
    {
        return $this->belongsTo(Jabatan::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Jabatan::class, 'manager_id');
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

}
