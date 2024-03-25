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
    
    public function index()
    {
        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();

            if ($users->hasRole(['Super-Admin', 'admin'])) {
                $leaveApps = LeaveApplication::where('status', 'pending')->paginate(10);
            } else if ($users->hasRole('Approver')) {
                // Query untuk mendapatkan pengajuan cuti yang memiliki unit yang sama dengan unit pengguna
               // Query untuk mendapatkan pengajuan cuti dari bawahan pengguna
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $leaveApps = LeaveApplication::whereIn('manager_id', $subordinateIds)->where('status', 'pending')->paginate(10);   

            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan cuti yang diajukan oleh pengguna
                $leaveApps = $users->leave_applications()->where('status', 'pending')->paginate(10);
            }
    
            return view('cuti.index', compact('leaveApps'));   
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
            'approver_id' => 'required'
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
            'approver_id' => $request->input('approver_id'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);
    
        // Cari pengguna (karyawan) berdasarkan ID yang diberikan dalam request
        $karyawan = User::find($request->input('user_id'));
    
        // Pastikan pengguna (karyawan) ditemukan
        if (!$karyawan) {
            // Jika tidak ditemukan, kembali dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Pengguna (karyawan) tidak ditemukan.');
        }
    
        // Dapatkan jabatan pengguna (karyawan)
        $jabatan = $karyawan->jabatan;
    
        // Pastikan jabatan ditemukan
        if (!$jabatan) {
            // Jika tidak ditemukan, kembali dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Jabatan pengguna (karyawan) tidak ditemukan.');
        }
    
        // Dapatkan approver untuk jabatan
        $approver = $jabatan->manager_id;
    
        // Jika approver ditemukan, atur approver_id pada pengajuan cuti
        if ($approver) {
            $leaveApplication->approver_id = $approver->manajer_id;
        }
    
        // Simpan pengajuan cuti
        $leaveApplication->save();
    
        // Tambahkan session flash message
        $message = 'Pengajuan cuti berhasil dibuat.';
        Session::flash('success', $message);
    
        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('pengajuan-cuti.index')->with('success', $message);
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
