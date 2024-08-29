<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view overtime', ['only' => ['index']]);
        $this->middleware('permission:tambah overtime', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit overtime', ['only' => ['edit','update', 'cancel']]);
        $this->middleware('permission:delete overtime', ['only' => ['destroy']]);
        $this->middleware('permission:approve overtime', ['only' => ['approve', 'reject']]);
    }
    
    public function index()
    {
        /// Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $overtimes = Overtime::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('overtime.index', compact('overtimes'));
    }

    public function approval(){
        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();

            if ($users->hasRole(['Super-Admin', 'admin'])) {
                $overtimes = Overtime::where('status', 'pending')->get();
            } else if ($users->hasRole('Approver')) {
                // Query untuk mendapatkan pengajuan cuti yang memiliki unit yang sama dengan unit pengguna
               
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $overtimes = Overtime::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->get();   
        
            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan cuti yang diajukan oleh pengguna
                $overtimes = $users->leave_applications()->where('status', 'pending')->get();
            }
    
            return view('overtime.approval-overtime', compact('overtimes'));   
            }
            abort(401);
    }


    public function riwayat()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
    
        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $overtimes = Overtime::where('user_id', $user->id)
            ->whereIn('status', ['rejected', 'approved', 'canceled'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('overtime.riwayat', compact('overtimes','user'));
    }

    public function create()
    {
        $users = User::pluck('name', 'id');
        $approver = Jabatan::pluck('name', 'id');
        return view('overtime.create', compact('users','approver'));
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
            'approver_id' => 'nullable',
            'level_approve' => 'required',
            
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
        $overtimes = Overtime::create([
            'user_id' => $request->input('user_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'interval' => $interval_formatted,
            'approver_id' => $approver_id,
            'level_approve' => $request->input('level_approve'),
            'keterangan' => $request->input('keterangan'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Pengajuan Lembur berhasil dibuat.';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('overtime');
    }

    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $overtimes = Overtime::findOrFail($id);

        if ($overtimes->level_approve === 1) {
            $overtimes->status = 'approved';
            $overtimes->level_approve = '0';
        } else {
            $overtimes->status = 'pending';
            $overtimes->level_approve = '1';
            $overtimes->approver_id = $user->karyawan->jabatan->manager_id;
        }
        
        $overtimes->approve($updatedBy);
        $overtimes->save();

        $message = 'Lembur Approved.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-overtime');

    }
    
    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $overimes = Overtime::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');

        // Simpan alasan reject
        $overimes->alasan_reject = $alasan_reject;

        $overimes->reject($updatedBy);
        $overimes->save();

        $message = 'Pengajuan Lembur Tidak Di Setujui.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-overtime');

    }

    public function show(string $id)
    {
        $overtime = Overtime::findOrFail($id);
        return view('overtime.edit', compact('overtime'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $overtimes = Overtime::find($id);
        return view('overtime.edit', compact('overtimes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'keterangan' => 'required|string|max:255',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);
    
        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
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
    
        // Cari pengajuan lembur berdasarkan ID dan update data
        $overtimes = Overtime::findOrFail($id);
        $overtimes->update([
            'user_id' => $request->input('user_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'interval' => $interval_formatted,
            'keterangan' => $request->input('keterangan'),
            // Tambahkan kolom lain yang perlu diperbarui
        ]);
    
        // Tambahkan session flash message
        $message = 'Pengajuan Lembur berhasil Di Edit.';
        Session::flash('successAdd', $message);
    
        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('overtime');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function laporan()
    {
        return view('overtime.search');
    }

    public function search(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Overtime::select(
                'overtimes.*',
                'users.name as user_name',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan'
            )
            ->join('users', 'overtimes.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->whereBetween('overtimes.start_date', [$startDate, $endDate])
            ->whereBetween('overtimes.end_date', [$startDate, $endDate]);

            if ($status) {
                $query->where('overtimes.status', $status);
            }
        
            $results = $query->get();

        return view('overtime.search_results', compact('results', 'status'));
    }

    public function getOverCount()
    {
        /** @var App\Models\User */
        $user = Auth::user();
        if ($user->hasRole('Super-Admin')) {
            return response()->json(['countOvertime' => 0]);
        }
        $subordinateIds = $user->karyawan->jabatan->subordinates->pluck('manager_id');
        $countOvertime = Overtime::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->count();  
        return response()->json(['countOvertime' => $countOvertime]); // Mengubah 'countovertime' menjadi 'overCount'
    }
    

}
