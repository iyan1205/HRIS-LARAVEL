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
    
        // Fetch attendance records along with the karyawan's name
        $attendance = Attendance::whereHas('karyawan', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with('karyawan:name,user_id') // Eager load the karyawan name
        ->get();
    
        // Fetch the karyawan's name
        $karyawanName = $attendance->first()->karyawan->name ?? null;
    
        return response()->json([
            'user_id' => $userId,
            'name' => $karyawanName,
            'attendance' => $attendance
        ]);
    }
    

    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'departemen' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'unit' => 'required|string',
            'jabatan' => 'required|string',
            'status' => 'required|string|in:hadir,ijin,sakit,pulang',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i:s',
            'lokasi' => 'nullable|string',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // Validate file types for foto
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the authenticated user
        $userId = Auth::id();

        // Handle file upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoFile = $request->file('foto');
            $fotoPath = $fotoFile->store('attendances', 'public'); // Store file in 'public/attendances' directory
        }

        // Create a new attendance record
        $attendance = Attendance::create([
            'user_id' => $userId,
            'nik' => $request->nik,
            'departemen' => $request->departemen,
            'jenis_kelamin' => $request->jenis_kelamin,
            'unit' => $request->unit,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath, // Save the file path in the database
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance
        ], 201);
    }
    
    public function getTime()
    {
        return response()->json(['server_time' => now()->toISOString()]);
    }
}
