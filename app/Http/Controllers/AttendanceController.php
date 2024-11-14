<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('attendance.index', compact('attendance'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'foto_jam_masuk' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto_jam_masuk')) {
            $path = $request->file('foto_jam_masuk')->store('attendance', 'public');
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
        $request->validate([
            'foto_jam_keluar' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto_jam_keluar')) {
            $path = $request->file('foto_jam_keluar')->store('attendance', 'public');
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

            return redirect()->route('attendance.index')->with('success', 'Berhasil Check-out');
        }

        return redirect()->route('attendance.index')->with('error', 'Belum Check-in');
    }

    public function laporan() {
        
    }
}