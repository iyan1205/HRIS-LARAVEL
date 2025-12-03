<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'manager_id',
        'level_approve',
        'file_upload',
        'total_days',
        'alasan_reject',
        'updated_by',
        'updated_by_atasan',
        'updated_at_atasan'

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updated_by() {
        return $this->belongsToMany(User::class);
    }

    public function LeaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approve($updatedBy)
    {
        $this->updated_by =  $updatedBy; 
    }

    public function cancel($updatedBy)
    {
        $this->status = 'canceled';
        $this->updated_by =  $updatedBy; 
    }

    public function reject($updatedBy)
    {
        $this->status = 'rejected';
        $this->updated_by =  $updatedBy; 
    }

    public static function getApplicationsStartingToday() 
    {
        return self::select(
                'leave_applications.id', 'leave_applications.created_at', 
                'karyawans.name AS nama_karyawan', 'karyawans.nik', 
                'jabatans.name AS jabatan', 
                'leave_applications.start_date', 'leave_applications.end_date', 
                'leave_applications.status', 'leave_applications.updated_by', 
                'leave_applications.updated_at', 'leave_applications.level_approve', 'leave_applications.total_days', 
                'leave_applications.alasan_reject', 'leave_applications.manager_id', 
                'leave_types.kategori_cuti', 'leave_types.name',
                'manajer.name AS atasan_langsung'
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('karyawans', 'leave_applications.user_id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->leftJoin('karyawans as manajer', 'leave_applications.manager_id', '=', 'manajer.id')
            ->whereDate('leave_applications.start_date', '<=', now()->toDateString())
            ->whereDate('leave_applications.end_date', '>=', now()->toDateString())
            ->where('leave_applications.status', '=', 'approved')
            ->get();
    }

    public static function countByStatusAndUser($status, $userId = null)
    {
        $query = self::where('status', $status);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->count();
    }
    
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id', 'user_id');
    }
    public function scopeQ_approve() {
        return self::select(
                'leave_applications.*',
                'leave_types.name as leave_type',
                'leave_types.kategori_cuti as kategori',
                'users.id as user_id',
                'karyawans.id as karyawan_id',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan',
                'units.name as nama_unit',
                'departemens.name as nama_departemen'
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('users', 'leave_applications.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->join('units', 'karyawans.unit_id', '=', 'units.id')
            ->join('departemens', 'karyawans.departemen_id', '=', 'departemens.id');
    }

    public function scopeQ_laporan() {
        return self::select(
                'leave_applications.*',
                'leave_types.name as leave_type',
                'leave_types.kategori_cuti as kategori',
                'users.id as user_id',
                'karyawans.id as karyawan_id',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan',
                'units.name as nama_unit',
                'departemens.name as nama_departemen'
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('users', 'leave_applications.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->join('units', 'karyawans.unit_id', '=', 'units.id')
            ->join('departemens', 'karyawans.departemen_id', '=', 'departemens.id');
    }
}
