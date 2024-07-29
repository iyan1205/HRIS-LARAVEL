<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_in_photo',
        'time_out',
        'time_out_photo',
        'status', //check_in dan check_out
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
