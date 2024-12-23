<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AttendanceController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah check-in dan belum check-out
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->latest()
                                ->first();

        return view('attendance.index', compact('attendance'));
    }

    public function checkIn(Request $request)
    {
        
        if($request->file('foto_jam_masuk')){
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()).'.'.$request->file('foto_jam_masuk')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_masuk'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/'.$name_img));
            $path = 'attendance/'.$name_img;
        }


        Attendance::create([
            'user_id' => Auth::id(),
            'jam_masuk' => now(),
            'foto_jam_masuk' => $path,
            'status' => 'hadir',
        ]);

        return redirect()->route('attendance.index')->with('success', 'Berhasil Check-in');
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

        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->first();

        if ($attendance) {
            $attendance->update([
                'jam_keluar' => now(),
                'foto_jam_keluar' => $path,
                'status' => 'pulang',
            ]);

            return redirect()->route('attendance.index')->with('success', 'Check-out berhasil.');
        }

        return redirect()->route('attendance.index')->with('error', 'Data absensi tidak ditemukan.');
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
    
        // Ambil data berdasarkan tanggal
        $date = $request->input('date');
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereDate('created_at', $date)
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Tampilkan view dengan hasil pencarian
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
    
}