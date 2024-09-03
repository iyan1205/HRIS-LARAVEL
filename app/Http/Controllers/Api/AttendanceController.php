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
    public function attendance()
    {
        $userId = Auth::id();

        $attendance = Attendance::where('user_id', $userId)->get();

    return response()->json([
        'user_id' => $userId,
        'attendance' => $attendance
    ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required',
            'nik' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'unit' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Pulang',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i:s',
            'lokasi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional, adjust as needed
        ]);

        // Handle the file upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('attendances', 'public\attendance_photos');
        }

        // Create a new attendance record
        $attendance = Attendance::create([
            'nik' => $request->nik,
            'departemen' => $request->departemen,
            'jenis_kelamin' => $request->jenis_kelamin,
            'unit' => $request->unit,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath,
        ]);

        return response()->json(['message' => 'Attendance submitted successfully', 'data' => $attendance], 201);
    }
    

}
