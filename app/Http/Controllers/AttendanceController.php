<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();
        return view('attendance.index', compact('attendance'));
    }

    public function create()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('attendance.create', compact('attendance'));
    }

    

    public function records(){
        $attendance = Attendance::where('user_id', Auth::id())
        ->whereDate('created_at', Carbon::today())
        ->get();
        return view('attendance.records', compact('attendance'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'time_in_photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();
        $photo = $request->file('time_in_photo');
        $extension = $photo->getClientOriginalExtension();
        $photoName = $user->name . '_' . Carbon::now()->format('YmdHis') . '.' . $extension;

        // Simpan foto ke dalam folder 'photos' di dalam folder storage/app/public/
        $photo->storeAs('photos/check-in', $photoName, 'public');

        $attendance = new Attendance([
            'user_id' => $user->id,
            'date' => Carbon::now(),
            'time_in' => Carbon::now()->format('H:i'),
            'time_in_photo' => $photoName,
            'status' => 'check_in',
        ]);

        $attendance->save();

        return redirect()->route('attendance.index')->with('successAdd', 'Checked in successfully!');
    }


    // public function edit($id)
    // {
    //     $attendance = Attendance::find($id);
    //     return view('attendance.update', compact('attendance'));
    // }

    public function checkOut(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'time_out_photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('time_out_photo')) {
            $path = $request->file('time_out_photo')->store('attendance_photos', 'public');
            $attendance->time_out_photo = $path;
        }

        $attendance->time_out = now();
        $attendance->status = 'check_out';
        $attendance->save();

        return redirect()->route('attendance.index')->with('success', 'Checked out successfully!');
    }
    
}
