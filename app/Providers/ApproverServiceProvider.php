<?php

namespace App\Providers;

use App\Models\Jabatan;

class ApproverServiceProvider
{
    public function getApproverForJabatan(Jabatan $jabatan)
    {
        if ($jabatan->manager) {
            return $jabatan->manager;
        }

        return null; // Jika tidak ada atasan langsung, kembalikan null
    }
}
