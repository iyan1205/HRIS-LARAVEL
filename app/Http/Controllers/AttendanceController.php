<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ReportHistory;
use App\Models\User;
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
        // Cek apakah user sudah check-in dan belum check-out
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->latest()
                                ->first();
        $totalAttendanceToday = Attendance::where('user_id', Auth::id())
        ->whereDate('created_at', Carbon::today())
        ->count();
        return view('attendance.index', compact('attendance','totalAttendanceToday'));
    }

    public function checkIn(Request $request)
    {
    $userId = Auth::id();

    // Cek apakah user sudah check-in hari ini
    $existingAttendance = Attendance::where('user_id', $userId)
        ->whereDate('jam_masuk', today())
        ->where('status', 'hadir')
        ->first();
    
    if ($existingAttendance) {
        return redirect()->route('attendance.list')->with('error', 'Anda sudah check-in hari ini.');
    }
        if($request->file('foto_jam_masuk')){
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()).'.'.$request->file('foto_jam_masuk')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_masuk'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/'.$name_img));
            $path = 'attendance/'.$name_img;
        }
        $ipAddress = $request->ip();

        // Parsing informasi perangkat menggunakan library Jenssegers Agent
        $agent = new Agent();
        $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
        $platform = $agent->platform(); // Contoh: Windows, iOS, Android
        $browser = $agent->browser();   // Contoh: Chrome, Safari

        $deviceInfo = "{$deviceType} | {$platform} | {$browser}";
        Attendance::create([
            'user_id' => Auth::id(),
            'jam_masuk' => now(),
            'ip_address' => $ipAddress,
            'device_info' => $deviceInfo,
            'foto_jam_masuk' => $path,
            'status' => 'hadir',
        ]);

        return redirect()->route('attendance.list')->with('successAdd', 'Berhasil Check-in');
    }

    public function checkOut(Request $request)
    {
        $path = null; // Initialize path variable

        if ($request->file('foto_jam_keluar')) {
            $manager = new ImageManager(new Driver()); // No need to pass a driver explicitly
            $name_img = hexdec(uniqid()) . '.' . $request->file('foto_jam_keluar')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_keluar')); // Correct method for reading and processing image
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/' . $name_img)); // Save to correct storage path
            $path = 'attendance/' . $name_img;
        }
        $ipAddress = $request->ip();

        // Parsing informasi perangkat menggunakan library Jenssegers Agent
        $agent = new Agent();
        $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
        $platform = $agent->platform(); // Contoh: Windows, iOS, Android
        $browser = $agent->browser();   // Contoh: Chrome, Safari

        $deviceInfo = "{$deviceType} | {$platform} | {$browser}";

        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->first();

        if ($attendance) {
            $attendance->update([
                'jam_keluar' => now(),
                'foto_jam_keluar' => $path,
                'ip_address' => $ipAddress,
                'device_info' => $deviceInfo,
                'status' => 'pulang',
            ]);

            return redirect()->route('attendance.list')->with('successAdd', 'Check-out berhasil.');
        }

        return redirect()->route('attendance.list')->with('error', 'Data absensi tidak ditemukan.');
    }

    public function list_attendance() {
        $attendance = Attendance::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('attendance.list', compact('attendance'));
    }

    public function find_attendance(Request $request){
        $request->validate([
            'date' => 'required|date',
        ]);
    
        $date = $request->input('date');
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereDate('created_at', $date)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('attendance.list', compact('attendance'));
    }

    public function laporan() {
        return view('attendance.laporan');
    }

    public function find_attendance_report(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data berdasarkan rentang tanggal
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        ReportHistory::create([
            'user_id' => Auth::id(), // Jika user login
            'start_date' => $startDate,
            'end_date' => $endDate,
            'ip_address' => $request->ip(),
            'name' => 'Absensi'    
        ]);

        $attendance = Attendance::with('user.karyawan.jabatan') // Eager load relasi
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();
    
        // Tampilkan view dengan hasil pencarian
        return view('attendance.list-laporan', compact('attendance', 'startDate', 'endDate'));
    }

    public function find_by() {
        $users = User::with('karyawan')->get()->mapWithKeys(function ($user) {
            return [$user->id => $user->karyawan->name ?? $user->name];
        });
        return view('attendance.findby', compact('users'));
    }

    public function find_attendance_reques(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        $attendance = Attendance::with(['user.karyawan.jabatan']) // Eager load relasi
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Tampilkan view dengan hasil pencarian
        return view('attendance.list_find', compact('attendance', 'startDate', 'endDate', 'userId'));
    }

    public function report_history_absensi(){
        $reporthistory = ReportHistory::with('user')->where('name','Absensi')->get();
        return view('attendance.report-history', compact('reporthistory'));
    }    
}