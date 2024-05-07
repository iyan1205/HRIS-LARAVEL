<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
       // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $leaveApplications = LeaveApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('cuti.index', compact('leaveApplications'));
    }

    public function approval(){

        $users = Auth::user();

        $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
        $leaveApplications = LeaveApplication::whereIn('manager_id', $subordinateIds)->where('status', 'pending')->get();   
    
        return view('cuti.approval-cuti', compact('leaveApplications'));   
        
    }

    public function riwayat(){
        $user = Auth::user();
    
        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $leaveApplications = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', ['rejected', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('cuti.riwayat', compact('leaveApplications'));
    }
    

    

    public function create()
    {
        $users = User::pluck('name', 'id'); //Select Nama Karyawan/User
        $approver = Jabatan::pluck('name', 'id');
        $enumValues = DB::select('SHOW COLUMNS FROM leave_types WHERE Field = "kategori_cuti"')[0]->Type;

        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $enumOptions = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        // Mendapatkan array berisi kategori cuti dan ID-nya
        $leaveTypes = array_combine($enumOptions, $enumOptions);
        return view('cuti.create', compact('users','approver','leaveTypes'));
    }
    
    public function getManagerForCreate($user_id)
    {
        $user = User::findOrFail($user_id);
        $manager_id = $user->karyawan->jabatan->manager_id;
    
        return response()->json(['manager_id' => $manager_id]);
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
            'manager_id' => 'nullable',
            'level_approve' => 'nullable'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        // Memeriksa apakah ada pengajuan sebelumnya yang masih dalam status pending
        $pendingApplications = LeaveApplication::where('user_id', $request->input('user_id'))
        ->where('status', 'pending')
        ->count();

        if ($pendingApplications > 0) {
            $message = 'Pengajuan sebelumnya masih dalam status pending. Silakan tunggu hingga pengajuan sebelumnya disetujui.';
            return redirect()->back()->withInput()->with('error', $message);
        }
        
         // Memeriksa saldo cuti pengguna
        $leaveBalance = LeaveBalance::where('user_id', $request->input('user_id'))->first();

        if (!$leaveBalance) {
            $message = 'Saldo cuti pengguna tidak ditemukan.';
            return redirect()->back()->withInput()->with('error', $message);
        }

        if ($leaveBalance->saldo_cuti <= 0) {
            $message = 'Sisa Cuti Sudah Habis.';
            return redirect()->back()->withInput()->with('error', $message);
        }

        // Ambil nilai manager_id dari user_id yang dipilih jika manager_id bernilai null
        $manager_id = $request->input('manager_id');
        if ($manager_id === null) {
            // Ambil user yang dipilih
            $selectedUser = User::findOrFail($request->input('user_id'));
            // Ambil manager_id dari user yang dipilih
            $manager_id = $selectedUser->karyawan->jabatan->manager_id;
        }

        // Menghitung jumlah hari cuti
        $start_date = Carbon::parse($request->input('start_date'));
        $end_date = Carbon::parse($request->input('end_date'));
        $total_days = $start_date->diffInDays($end_date) + 1; // Jumlah hari termasuk tanggal start_date dan end_date

        // Buat dan simpan jabatan baru
        $leaveApplication = LeaveApplication::create([
            'user_id' => $request->input('user_id'),
            'leave_type_id' => $request->input('leave_type_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_days' => $total_days,
            'manager_id' => $manager_id,
            'level_approve' => $request->input('level_approve'),
            
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
    
        // 1 Approved
        if ($leaveApplication->level_approve === 1) {
            // Jika leave_type_id mempunyai kategori cuti "CUTI KHUSUS", saldo tidak dikurangi
            if ($leaveApplication->leaveType->kategori_cuti === 'CUTI KHUSUS') {
                // Set status menjadi approved
                $leaveApplication->status = 'approved';
            } else {
                // Kurangi saldo cuti
                $total_days = $leaveApplication->total_days;
                $leaveBalance = LeaveBalance::where('user_id', $leaveApplication->user_id)->firstOrFail();
                $leaveBalance->saldo_cuti -= $total_days;
                $leaveBalance->save();

                // Set status menjadi approved
                $leaveApplication->status = 'approved';
            }
        }
    
        // Jika level_approve adalah 2, maka ubah status menjadi stage 1 dan tidak mengurangi saldo
        elseif ($leaveApplication->level_approve === 2) {
            // Update level approve
            $leaveApplication->level_approve = '1';
            // Update manager_id
            $leaveApplication->manager_id = $user->karyawan->jabatan->manager_id;
        }
    
        // Menyetujui aplikasi cuti
        $leaveApplication->approve($updatedBy);
    
        // Simpan perubahan pada aplikasi cuti
        $leaveApplication->save();
    
        $message = 'Pengajuan cuti Approved.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-cuti');
    }
    

    public function cancel(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $leaveApplication = LeaveApplication::findOrFail($id);
        $leaveApplication->cancel($updatedBy);
        $leaveApplication->save();

        $message = 'Approved: Canceled.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-cuti');

    }

    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $leaveApplication = LeaveApplication::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');

        // Simpan alasan reject
        $leaveApplication->alasan_reject = $alasan_reject;

        $leaveApplication->reject($updatedBy);
        $leaveApplication->save();

        $message = 'Pengajuan cuti Tidak Disetujui.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-cuti');

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

    function laporan() {
        return view('cuti.search');
    }

    public function search(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
    
        $query = LeaveApplication::select(
                'leave_applications.*',
                'leave_types.name as leave_type',
                'users.name as user_name'
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('users', 'leave_applications.user_id', '=', 'users.id')
            ->whereBetween('leave_applications.start_date', [$startDate, $endDate])
            ->whereBetween('leave_applications.end_date', [$startDate, $endDate]);
    
        if ($status) {
            $query->where('leave_applications.status', $status);
        }
    
        $results = $query->get();
        
        return view('cuti.search_results', compact('results', 'status'));
    }
    


}
