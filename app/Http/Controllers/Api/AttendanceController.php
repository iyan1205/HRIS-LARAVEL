<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class AttendanceController extends Controller
{
    public function getTodayAttendance()
    {
         // Cek apakah user sudah check-in dan belum check-out
         $attendance = Attendance::where('user_id', Auth::id())
         ->whereNull('jam_keluar')
         ->latest()
         ->first();
        return response()->json($attendance);
    }
    

    public function checkIn(Request $request)
    {
        $path = null; // Initialize path variable
    
        if ($request->file('foto_jam_masuk')) {
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()) . '.' . $request->file('foto_jam_masuk')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_masuk'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/' . $name_img));
            $path = 'attendance/' . $name_img;
        }
    
        Attendance::create([
            'user_id' => Auth::id(),
            'jam_masuk' => now(),
            'foto_jam_masuk' => $path,
            'status' => 'hadir',
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Check-in',
            'data' => [
                'jam_masuk' => now(),
                'foto_jam_masuk' => $path,
            ]
        ], 200);
    }
    
    public function checkOut(Request $request)
    {
        $path = null; // Initialize path variable
    
        if ($request->file('foto_jam_keluar')) {
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
    
        if ($attendance) {
            $attendance->update([
                'jam_keluar' => now(),
                'foto_jam_keluar' => $path,
                'status' => 'pulang',
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil.',
                'data' => [
                    'jam_keluar' => now(),
                    'foto_jam_keluar' => $path,
                ]
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Data absensi tidak ditemukan.',
        ], 404);
    }
    

}