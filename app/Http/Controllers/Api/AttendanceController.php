<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function getTodayAttendance(Request $request)
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
        ->whereDate('created_at', Carbon::today())
        ->latest()
        ->first();
        return response()->json($attendances);
    }
    

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'foto' => 'required|image|mimes:jpg,jpeg', 
            'status' => 'required|in:hadir,pulang',
            'jam' => 'required|date_format:H:i'
        ]);
    
        // Simpan file foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('photos/attendance'), $filename); // Simpan file di folder 'uploads'
        } else {
            $filename = null; // Jika tidak ada file, set ke null
        }
    
        // Buat data ke dalam tabel Attendance
        $attendance = Attendance::create([
            'user_id' => $request->input('user_id'),
            'tanggal' => $request->input('tanggal'),
            'foto' => $filename, // Simpan nama file foto
            'status' => $request->input('status'),
            'jam' => $request->input('jam')
        ]);
    
        // Respons JSON
        return response()->json([
            'message' => 'Attendance created successfully',
            'data' => $attendance
        ], 201);
    }
    

}