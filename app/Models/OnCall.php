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
    public function scopeQ_oncall()
    {
        return self::select(
                'on_calls.*',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan',
                'units.name as nama_unit',
                'departemens.name as nama_departemen'
            )
            ->join('users', 'on_calls.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->join('units', 'karyawans.unit_id', '=', 'units.id')
            ->join('departemens', 'karyawans.departemen_id', '=', 'departemens.id');
    }
}
