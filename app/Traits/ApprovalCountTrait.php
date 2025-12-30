<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ApprovalCountTrait
{
    /**
     * Mendapatkan jumlah item yang menunggu persetujuan
     */
    protected function getPendingCountForUser($modelClass, $kolomApprover = 'approver_id')
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        // Admin melihat semua
        if ($user->hasRole(['admin', 'Super-Admin'])) {
            return $modelClass::pending()->count(); // Menggunakan scope
        }

        $karyawan = $user->karyawan;

        if (!$karyawan || !$karyawan->jabatan) {
            return 0;
        }

        $jabatan = $karyawan->jabatan;
        
        // Ambil ID bawahan
        $idsBawahan = $jabatan->subordinates()
            ->pluck('manager_id')
            ->toArray();

        if (empty($idsBawahan)) {
            return 0;
        }

        return $modelClass::pending() // Menggunakan scope
            ->whereIn($kolomApprover, $idsBawahan)
            ->count();
    }
}