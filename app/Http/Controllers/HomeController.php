<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\LeaveApplication;
use App\Models\Overtime;
use App\Models\OnCall;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public function index()
    {
        $jumlahKaryawanAktif = Karyawan::countByStatus('active');
        $jumlahKaryawanResign = Karyawan::countByStatus('resign');
        $karyawanCount = User::countByRole('karyawan');
        $pengajuanReject = 0;
        $pengajuanApproved = 0;
        $lemburpending = 0;
        $lemburrejected = 0;
        $lemburapproved = 0;
        $oncallpending = 0;
        $oncallrejected = 0;
        $oncallapproved = 0;
        $leaveApplicationsToday = 0;

        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();
            $userId = auth()->id();
        if ($users->hasRole(['Super-Admin', 'admin'])) {
            $pengajuanCuti = LeaveApplication::where('status', 'pending')->count();
            $leaveApplicationsToday = LeaveApplication::getApplicationsStartingToday();
        }else{
            $pengajuanCuti = LeaveApplication::countByStatusAndUser('pending', $userId);
            $pengajuanReject = LeaveApplication::countByStatusAndUser('rejected', $userId);
            $pengajuanApproved = LeaveApplication::countByStatusAndUser('approved', $userId);
            $lemburpending = Overtime::countByStatusAndUser('pending', $userId);
            $lemburrejected = Overtime::countByStatusAndUser('rejected', $userId);
            $lemburapproved = Overtime::countByStatusAndUser('approved', $userId);
            $oncallpending = OnCall::countByStatusAndUser('pending', $userId);
            $oncallrejected = OnCall::countByStatusAndUser('rejected', $userId);
            $oncallapproved = OnCall::countByStatusAndUser('approved', $userId);
        }

    }
        return view('dashboard', compact(
            'jumlahKaryawanAktif','jumlahKaryawanResign','karyawanCount',
            'pengajuanCuti','pengajuanReject', 'pengajuanApproved',
            'lemburpending','lemburrejected','lemburapproved',
            'oncallpending','oncallrejected','oncallapproved','leaveApplicationsToday',
        ));
    }

}
