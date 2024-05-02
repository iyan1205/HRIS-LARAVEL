<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\OnCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OnCallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $oncalls = OnCall::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('oncall.index', compact('oncalls'));
    }

    public function approval(){
        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();

            if ($users->hasRole(['Super-Admin'])) {
                $oncalls = OnCall::where('status', 'pending')->get();
            } else if ($users->hasRole('Approver')) {
                // Query untuk mendapatkan pengajuan cuti yang memiliki unit yang sama dengan unit pengguna
               
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $oncalls = OnCall::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->get();   
        
            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan cuti yang diajukan oleh pengguna
                $oncalls = $users->oncall()->where('status', 'pending')->get();
            }
    
            return view('oncall.approval-oncall', compact('oncalls'));   
            }
            abort(401);
    }

    public function riwayat()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
    
        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $oncalls = OnCall::where('user_id', $user->id)
            ->whereIn('status', ['rejected', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('oncall.riwayat', compact('oncalls'));
    }

    public function create()
    {
        $users = User::pluck('name', 'id');
        $approver = Jabatan::pluck('name', 'id');
        return view('oncall.create', compact('users','approver'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'approver_id' => 'nullable'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        // Ambil nilai approver_id dari user_id yang dipilih jika approver_id bernilai null
        $approver_id = $request->input('approver_id');
        if ($approver_id === null) {
            // Ambil user yang dipilih
            $selectedUser = User::findOrFail($request->input('user_id'));
            // Ambil approver_id dari user yang dipilih
            $approver_id = $selectedUser->karyawan->jabatan->approver_id;
        }

        // Menghitung interval waktu
        $start_date = Carbon::parse($request->input('start_date'));
        $end_date = Carbon::parse($request->input('end_date'));
        $interval = $start_date->floatDiffInRealMinutes($end_date); // Interval dalam menit

        // Menghitung jumlah hari, jam, dan menit
        $days = floor($interval / (24 * 60)); // Mendapatkan jumlah hari
        $remaining_minutes = $interval % (24 * 60); // Sisa menit setelah dihitung hari

        $hours = floor($remaining_minutes / 60); // Mendapatkan jumlah jam
        $minutes = $remaining_minutes % 60; // Sisa menit setelah dihitung jam

        // Format interval waktu ke dalam "hari jam menit"
        $interval_formatted = '';
        if ($days > 0) {
            $interval_formatted .= $days . ' hari ';
        }
        if ($hours > 0) {
            $interval_formatted .= $hours . ' jam ';
        }
        if ($minutes > 0) {
            $interval_formatted .= $minutes . ' menit';
        }

        // Buat dan simpan jabatan baru
        $oncalls = OnCall::create([
            'user_id' => $request->input('user_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'interval' => $interval_formatted,
            'approver_id' => $approver_id,
            'keterangan' => $request->input('keterangan'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);


        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('oncall');
    }

    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $oncalls = OnCall::findOrFail($id);

        $oncalls->approve($updatedBy);
        $oncalls->save();

        $message = 'Lembur Approved.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-oncall');

    }
    
    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $overimes = OnCall::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');

        // Simpan alasan reject
        $overimes->alasan_reject = $alasan_reject;

        $overimes->reject($updatedBy);
        $overimes->save();

        $message = 'Pengajuan Lembur Tidak Di Setujui.';
        Session::flash('reject', $message);
        return redirect()->route('approval-oncall');

    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function search(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $results = OnCall::select(
                'on_calls.*',
                'users.name as user_name'
            )
            ->join('users', 'on_calls.user_id', '=', 'users.id')
            ->whereBetween('on_calls.start_date', [$startDate, $endDate])
            ->whereBetween('on_calls.end_date', [$startDate, $endDate])
            ->get();

        return view('oncall.search_results', compact('results'));
    }
}
