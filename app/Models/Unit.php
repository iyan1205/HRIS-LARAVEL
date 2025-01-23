<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',

    ];

    protected $hidden = [
        'remember_token',
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}
