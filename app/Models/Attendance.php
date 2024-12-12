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
        'status', // hadir, pulang
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalDurationAttribute()
    {
        if (!$this->created_at || !$this->updated_at) {
            return null; // Handle missing timestamps
        }
    
        $durationInMinutes = $this->created_at->diffInMinutes($this->updated_at);
        $hours = intdiv($durationInMinutes, 60);
        $minutes = $durationInMinutes % 60;
    
        if ($hours >= 24) {
            return "Tidak absen pulang";
        }
    
        return sprintf('%d Jam %d Menit', $hours, $minutes);
    }

}

