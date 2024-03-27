<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LeaveApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view cuti', ['only' => ['index']]);
        $this->middleware('permission:tambah cuti', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit cuti', ['only' => ['edit','update']]);
        $this->middleware('permission:delete cuti', ['only' => ['destroy']]);
        $this->middleware('permission:approve cuti', ['only' => ['approve', 'cancel', 'reject']]);
    }
    
    public function index()
    {
        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();

            if ($users->hasRole(['Super-Admin', 'admin'])) {
                $leaveApplication = LeaveApplication::where('status', 'pending')->get();
            } else if ($users->hasRole('Approver')) {
                // Query untuk mendapatkan pengajuan cuti yang memiliki unit yang sama dengan unit pengguna
               
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $leaveApplication = LeaveApplication::whereIn('manager_id', $subordinateIds)->where('status', 'pending')->get();   
        
            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan cuti yang diajukan oleh pengguna
                $leaveApplication = $users->leave_applications()->where('status', 'pending')->get();
            }
    
            return view('cuti.index', compact('leaveApplication'));   
            }
            abort(401);
    }

    public function create()
    {
        $users = User::pluck('name', 'id');
        $approver = Jabatan::pluck('name', 'id');
        $leave_types = LeaveType::pluck('name','id');
        return view('cuti.create', compact('users','approver','leave_types'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validasi input dari form
         $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'leave_type_id' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'manager_id' => 'required'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);
    
        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
   
        // Buat dan simpan jabatan baru
        $leaveApplication = LeaveApplication::create([
            'user_id' => $request->input('user_id'),
            'leave_type_id' => $request->input('leave_type_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'manager_id' => $request->input('manager_id'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);
    
          
        // Tambahkan session flash message
        $message = 'Pengajuan cuti berhasil dibuat.';
        Session::flash('successAdd', $message);
    
        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('pengajuan-cuti');
    }
    
    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveApplication->approve($updatedBy);
        $leaveApplication->save();

        $message = 'Pengajuan cuti Approved.';
        Session::flash('successAdd', $message);
        return redirect()->route('pengajuan-cuti');

    }

    public function cancel(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveApplication->cancel($updatedBy);
        $leaveApplication->save();

        $message = 'Approved: Canceled.';
        Session::flash('successAdd', $message);
        return redirect()->route('pengajuan-cuti');

    }

    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveApplication->reject($updatedBy);
        $leaveApplication->save();

        $message = 'Pengajuan cuti rejected.';
        Session::flash('reject', $message);
        return redirect()->route('pengajuan-cuti');

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
