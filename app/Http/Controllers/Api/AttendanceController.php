<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->latest()
                                ->first();
        $totalAttendanceToday = Attendance::where('user_id', Auth::id())
                                          ->whereDate('created_at', Carbon::today())
                                          ->count();
        
        return response()->json(['attendanceToday' => $attendance, 'totalAttendanceToday' => $totalAttendanceToday]);
    }

    public function checkIn(Request $request)
    {
        $userId = Auth::id();
    
        // Cek apakah user sudah check-in hari ini dengan status 'hadir'
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('jam_masuk', today())
            ->where('status', 'hadir')
            ->first();
    
        if ($existingAttendance) {
            return response()->json([
                'message' => 'Anda sudah check-in hari ini',
                'attendance' => $existingAttendance
            ], 400);
        }
    
        $path = null;
        if ($request->hasFile('foto_jam_masuk')) {
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()) . '.' . $request->file('foto_jam_masuk')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_masuk'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/' . $name_img));
            $path = 'attendance/' . $name_img;
        }
    
        $ipAddress = $request->ip();
        $agent = new Agent();
        $deviceInfo = ($agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop')) . " | " . $agent->platform() . " | " . $agent->browser();
    
        $attendance = Attendance::create([
            'user_id' => $userId,
            'jam_masuk' => now(),
            'ip_address' => $ipAddress,
            'device_info' => $deviceInfo,
            'foto_jam_masuk' => $path,
            'status' => 'hadir',
        ]);
    
        return response()->json([
            'message' => 'Berhasil Check-in',
            'attendance' => $attendance
        ], 201);
    }
    

    public function checkOut(Request $request)
    {
        $path = null;
        if ($request->hasFile('foto_jam_keluar')) {
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()) . '.' . $request->file('foto_jam_keluar')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_keluar'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/' . $name_img));
            $path = 'attendance/' . $name_img;
        }

        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->first();
        
        if (!$attendance) {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }

        $attendance->update([
            'jam_keluar' => now(),
            'foto_jam_keluar' => $path,
            'status' => 'pulang',
        ]);

        return response()->json(['message' => 'Check-out berhasil', 'attendance' => $attendance]);
    }

    public function listAttendance()
    {
        $attendance = Attendance::where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        return response()->json(['attendance' => $attendance]);
    }

    public function findAttendance(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereDate('created_at', $request->input('date'))
                                ->orderBy('created_at', 'desc')
                                ->get();
        return response()->json(['attendance' => $attendance]);
    }

    public function reportHistory()
    {
        $reportHistory = ReportHistory::where('user_id', Auth::id())->where('name', 'Absensi')->get();
        return response()->json(['report_history' => $reportHistory]);
    }
}
