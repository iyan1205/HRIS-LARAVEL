<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use ReflectionFunctionAbstract;
use Spatie\Permission\Traits\HasRoles;

//
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class);
    // }

    public function leave_applications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function leave_balances()
    {
        return $this->hasOne(LeaveBalance::class);
    }
    
    public function overtime(){
        return $this->hasMany(Overtime::class);
    }
    
    public function oncall(){
        return $this->hasMany(OnCall::class);
    }
    public function scopeActiveKaryawan($query)
    {
        return $query->whereHas('karyawan', function ($q) {
            $q->where('status', 'active');
        })->with('karyawan');
    }

    public function getActiveUsersByDepartment($departemenId)
    {
        return $this->whereHas('karyawan', function ($query) use ($departemenId) {
            $query->where('departemen_id', $departemenId)
                ->where('status', 'active');
        })
        ->with('karyawan')
        ->get()
        ->sortBy(fn($user) => $user->karyawan->name)
        ->pluck('karyawan.name', 'id');
    }

    public static function countByRole($role)
    {
        return self::role($role)->count();
    }

}
