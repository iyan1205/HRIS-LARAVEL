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
        // Validate incoming request data
        $validatedData = $request->validate([
            'user_id'   => 'required|uuid',
            'tanggal'   => 'required|date',
            'jam'       => 'required',
            'status'    => 'required|string|max:255',
            'foto'      => 'required|string', // Assuming the photo is base64 encoded
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Save data to the database
        $data = new Attendance(); // Replace with your actual model
        $data->user_id = $validatedData['user_id'];
        $data->tanggal = $validatedData['tanggal'];
        $data->jam = $validatedData['jam'];
        $data->status = $validatedData['status'];
        $data->foto = $validatedData['foto'];
        $data->latitude = $validatedData['latitude'];
        $data->longitude = $validatedData['longitude'];
        $data->save();

        // Return a response
        return response()->json([
            'success' => true,
            'message' => 'Data stored successfully',
            'data' => $data
        ], 201);
    }
    

}
