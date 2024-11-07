<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnCall extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'interval',
        'status',
        'keterangan',
        'approver_id',
        'level_approve',
        'updated_by',
        'updated_by_atasan',
        'updated_at_atasan',

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

    public static function countByStatusAndUser($status, $userId = null)
    {
        $query = self::where('status', $status);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->count();
    }
    
}
