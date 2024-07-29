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
    public function index(Request $request)
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)->get();
        return response()->json($attendances);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now(); // waktu server

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if (!$existingAttendance) {
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->date = $now->toDateString();
            $attendance->check_in = $now->toTimeString();
            $attendance->save();

            return response()->json(['message' => 'Checked in successfully', 'attendance' => $attendance], 200);
        } else {
            return response()->json(['message' => 'Already checked in today'], 400);
        }
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now(); // waktu server

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance && $attendance->check_out === null) {
            $attendance->check_out = $now->toTimeString();
            $attendance->save();

            return response()->json(['message' => 'Checked out successfully', 'attendance' => $attendance], 200);
        } else {
            return response()->json(['message' => 'Already checked out today or not checked in yet'], 400);
        }
    }
}
