<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\LeaveApplication;
use App\Models\Overtime;
use App\Models\OnCall;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{

    public function index()
    {
        // ✅ 1 query untuk hitung karyawan (pakai cache 5 menit)
        $karyawanStats = Cache::remember('karyawan_stats', 300, fn() => [
            'aktif'   => Karyawan::countByStatus('active'),
            'resign'  => Karyawan::countByStatus('resign'),
            'count'   => User::countByRole('karyawan'),
        ]);

        $jumlahKaryawanAktif = $karyawanStats['aktif'];
        $jumlahKaryawanResign = $karyawanStats['resign'];
        $karyawanCount = $karyawanStats['count'];

        // Default values
        $pengajuanCuti = $pengajuanReject = $pengajuanApproved = 0;
        $lemburpending = $lemburrejected = $lemburapproved = 0;
        $oncallpending = $oncallrejected = $oncallapproved = 0;
        $leaveApplicationsToday = 0;

        if (!Auth::check()) {
            return view('dashboard', compact(...));
        }

        /** @var \App\Models\User */
        $user   = Auth::user();
        $userId = $user->id;

        if ($user->hasRole(['Super-Admin', 'admin'])) {

            // ✅ 2 query saja untuk admin
            $pengajuanCuti          = LeaveApplication::where('status', 'pending')->count();
            $leaveApplicationsToday = LeaveApplication::getApplicationsStartingToday();

        } else {

            // ✅ Gabungkan 3 query LeaveApplication → 1 query groupBy
            $leaveStats = LeaveApplication::where('user_id', $userId)
                ->whereIn('status', ['pending', 'rejected', 'approved'])
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');  // ['pending' => 2, 'approved' => 5]

            $pengajuanCuti    = $leaveStats['pending']  ?? 0;
            $pengajuanReject  = $leaveStats['rejected'] ?? 0;
            $pengajuanApproved = $leaveStats['approved'] ?? 0;

            // ✅ Gabungkan 3 query Overtime → 1 query groupBy
            $lemburStats = Overtime::where('user_id', $userId)
                ->whereIn('status', ['pending', 'rejected', 'approved'])
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $lemburpending  = $lemburStats['pending']  ?? 0;
            $lemburrejected = $lemburStats['rejected'] ?? 0;
            $lemburapproved = $lemburStats['approved'] ?? 0;

            // ✅ Gabungkan 3 query OnCall → 1 query groupBy
            $oncallStats = OnCall::where('user_id', $userId)
                ->whereIn('status', ['pending', 'rejected', 'approved'])
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $oncallpending  = $oncallStats['pending']  ?? 0;
            $oncallrejected = $oncallStats['rejected'] ?? 0;
            $oncallapproved = $oncallStats['approved'] ?? 0;
        }

        return view('dashboard', compact(
            'jumlahKaryawanAktif', 'jumlahKaryawanResign', 'karyawanCount',
            'pengajuanCuti', 'pengajuanReject', 'pengajuanApproved',
            'lemburpending', 'lemburrejected', 'lemburapproved',
            'oncallpending', 'oncallrejected', 'oncallapproved',
            'leaveApplicationsToday',
        ));
    }

}
