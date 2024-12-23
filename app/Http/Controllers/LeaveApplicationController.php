<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LeaveApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view cuti', ['only' => ['index']]);
        $this->middleware('permission:tambah cuti', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit cuti', ['only' => ['edit','update','cancel']]);
        $this->middleware('permission:delete cuti', ['only' => ['destroy']]);
        $this->middleware('permission:approve cuti', ['only' => ['approve']]);
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
            ->whereIn('status', ['rejected', 'approved','canceled'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('cuti.riwayat', compact('leaveApplications', 'user'));
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
            $message = 'Pengajuan Sebelumnya Belum Di Setujui !';
            return redirect()->back()->withInput()->with('error', $message);
        }
        
         // Periksa apakah jenis cuti memerlukan pengecekan saldo
        $leaveType = LeaveType::find($request->input('leave_type_id'));
        if ($leaveType->cek_saldo) {
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

        // Jika kategori cuti adalah "CUTI KHUSUS", lakukan validasi file dan simpan file ke dalam folder file_cuti
        if ($request->kategori_cuti === 'CUTI KHUSUS' || $request->leave_type_id === '1') {
            $validator = Validator::make($request->all(), [
                'file_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Max size 2MB
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            // Simpan file ke dalam folder file_cuti
            $file = $request->file('file_upload');
             // Generate nama file berdasarkan tanggal upload
            $fileName = Carbon::now()->format('Y-m-d') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan file ke dalam folder file_cuti dengan nama yang telah diubah
            $path = $file->storeAs('file_cuti', $fileName, 'public'); // Folder file_cuti di dalam storage/app/public
        } else {
            // Jika kategori cuti bukan "CUTI KHUSUS", set nilai path file menjadi null
            $path = null;
        }
        // Buat dan simpan jabatan baru
        $leaveApplication = LeaveApplication::create([
            'user_id' => $request->input('user_id'),
            'leave_type_id' => $request->input('leave_type_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_days' => $total_days,
            'manager_id' => $manager_id,
            'level_approve' => $request->input('level_approve'),
            'file_upload' => $path,
            
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
    
        // Level 1 Untuk yang tidak memiliki Atasan langsung
        if ($leaveApplication->level_approve === 1) {
            // Jika leave_type_id mempunyai saldo_cuti "no", saldo tidak dikurangi
            if ($leaveApplication->leaveType->saldo_cuti === 'no') {
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
            $leaveApplication->level_approve = '0';
        }
    
        // Level 2 Untuk Yang memiliki Atasan Langsung
        elseif ($leaveApplication->level_approve === 2) {
            // Update level approve
            $leaveApplication->level_approve = '1';
            // Update manager_id
            $leaveApplication->manager_id = $user->karyawan->jabatan->manager_id;
            $leaveApplication->updated_by_atasan = $updatedBy;
            $leaveApplication->updated_at_atasan = now();
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

        $message = 'Pengajuan Dibatalkan.';
        Session::flash('successAdd', $message);
        return redirect()->route('pengajuan-cuti');

    }

    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $leaveApplication = LeaveApplication::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');
        $leaveApplication->alasan_reject = $alasan_reject;

           // If the leave is approved and the category is "CUTI TAHUNAN", update the leave balance
        if ($leaveApplication->status == 'approved' && $leaveApplication->leavetype->kategori_cuti == 'CUTI TAHUNAN') {
            // Find the user's leave balance record
            $leaveBalance = LeaveBalance::where('user_id', $leaveApplication->user_id)->first();
            if ($leaveBalance) {
                // Add total_days of the rejected leave back to saldo_cuti
                $leaveBalance->saldo_cuti += $leaveApplication->total_days;
                $leaveBalance->save();
            }
        }

        $leaveApplication->reject($updatedBy);
        $leaveApplication->save();

        $message = 'Pengajuan cuti Tidak Disetujui.';
        Session::flash('successAdd', $message);
        return redirect()->back();

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

        public function getLeaveTypes($kategori)
    {
        $leaveTypes = LeaveType::where('kategori_cuti', $kategori)->pluck('name', 'id');
        return response()->json($leaveTypes);
    }


    public function edit(Request $request,$id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        $users = User::pluck('name', 'id');
        $approver = Jabatan::pluck('name', 'id');
    
        // Fetch categories for leave types
        $kategori_cuti = LeaveType::distinct()->pluck('kategori_cuti', 'kategori_cuti');
        // Get the current category for the leave application
        $currentCategory = LeaveType::find($leaveApplication->leave_type_id)->kategori_cuti ?? '';
        
        // Fetch leave types
        $leaveTypes = LeaveType::where('kategori_cuti', $currentCategory)->pluck('name', 'id');
    
    
        // Convert date strings to Carbon instances
        $leaveApplication->start_date = \Carbon\Carbon::parse($leaveApplication->start_date);
        $leaveApplication->end_date = \Carbon\Carbon::parse($leaveApplication->end_date);
    
    
        return view('cuti.edit', compact('leaveApplication', 'users', 'approver', 'leaveTypes', 'kategori_cuti', 'currentCategory'));
    }
    
    
    public function update(Request $request, $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
    
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
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        // Memeriksa apakah ada pengajuan sebelumnya yang masih dalam status pending
        $pendingApplications = LeaveApplication::where('user_id', $request->input('user_id'))
        ->where('status', 'pending')
        ->where('id', '!=', $id) // Mengabaikan pengajuan ini
        ->count();
    
        if ($pendingApplications > 0) {
            $message = 'Pengajuan Sebelumnya Belum Di Setujui !';
            return redirect()->back()->withInput()->with('error', $message);
        }
    
        // Periksa apakah jenis cuti memerlukan pengecekan saldo
        $leaveType = LeaveType::find($request->input('leave_type_id'));
        if ($leaveType->cek_saldo) {
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
        }
    
        // Ambil nilai manager_id dari user_id yang dipilih jika manager_id bernilai null
        $manager_id = $request->input('manager_id');
        if ($manager_id === null) {
            $selectedUser = User::findOrFail($request->input('user_id'));
            $manager_id = $selectedUser->karyawan->jabatan->manager_id;
        }
    
        // Menghitung jumlah hari cuti
        $start_date = Carbon::parse($request->input('start_date'));
        $end_date = Carbon::parse($request->input('end_date'));
        $total_days = $start_date->diffInDays($end_date) + 1;
    
        $path = $leaveApplication->file_upload;
    
        if ($request->kategori_cuti === 'CUTI KHUSUS' || $request->leave_type_id === '1') {
            $validator = Validator::make($request->all(), [
                'file_upload' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048', // Max size 2MB
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
    
            if ($request->hasFile('file_upload')) {
                $file = $request->file('file_upload');
                $fileName = Carbon::now()->format('Y-m-d') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('file_cuti', $fileName, 'public');
            }
        }
    
        $leaveApplication->update([
            'user_id' => $request->input('user_id'),
            'leave_type_id' => $request->input('leave_type_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_days' => $total_days,
            'manager_id' => $manager_id,
            'level_approve' => $request->input('level_approve'),
            'file_upload' => $path,
        ]);
    
        $message = 'Pengajuan cuti berhasil diperbarui.';
        Session::flash('successAdd', $message);
    
        return redirect()->route('pengajuan-cuti');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchcuti(User $user){
        /** @var App\Models\User */
    $authUser = Auth::user();
    if ($authUser->hasRole('admin')) {
        $users = $user->activeKaryawan() // Using the injected User instance
            ->get()
            ->sortBy(fn($user) => $user->karyawan->name)
            ->mapWithKeys(fn($user) => [$user->id => $user->karyawan->name]);
    } else {
        $karyawan = Karyawan::where('user_id', $authUser->id)->firstOrFail(); 
         $users = $user->getActiveUsersByDepartment($karyawan->departemen_id); 
    }
        return view('cuti.searchcuti', compact('users'));
    }

    public function searchapprove(Request $request){
        $users = $request->input('user_id');
        $startDate = $request->input('start_date');

        $query = LeaveApplication::select(
                'leave_applications.*',
                'leave_types.name as leave_type', 
                'leave_types.kategori_cuti as kategori',
                'users.name as user_name',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan',
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('users', 'leave_applications.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->where('leave_applications.status', 'approved');
        // Pastikan ada nilai untuk user_id,
        if ($users) {
            $query->where('users.id', $users);
        }

        if ($startDate) {
            $query->where('leave_applications.start_date', '=', $startDate);
        }

        $results = $query->get();
        
        return view('cuti.results_approve', compact('results'));
        }


    function laporan() {
        return view('cuti.search');
    }

    public function search(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        ReportHistory::create([
            'user_id' => Auth::id(), // Jika user login
            'start_date' => $startDate,
            'end_date' => $endDate,
            'ip_address' => $request->ip(),
            'name' => 'Pengajuan Cuti'    
        ]);

        $query = LeaveApplication::select(
                'leave_applications.*',
                'leave_types.name as leave_type', 'leave_types.kategori_cuti as kategori',
                'users.name as user_name',
                'karyawans.name as karyawan_name',
                'jabatans.name as nama_jabatan'
            )
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
            ->join('users', 'leave_applications.user_id', '=', 'users.id')
            ->join('karyawans', 'users.id', '=', 'karyawans.user_id')
            ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->whereBetween('leave_applications.start_date', [$startDate, $endDate])
            ->whereBetween('leave_applications.end_date', [$startDate, $endDate]);
    
        if ($status) {
            $query->where('leave_applications.status', $status);
        }
        
        $results = $query->get();
        
        return view('cuti.search_results', compact('results', 'status'));
    }
    
    public function cancel_approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $leaveApplication = LeaveApplication::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');
        $leaveApplication->alasan_reject = $alasan_reject;

           // If the leave is approved and the category is "CUTI TAHUNAN", update the leave balance
        if ($leaveApplication->status == 'approved' && 
            $leaveApplication->leavetype->kategori_cuti == 'CUTI TAHUNAN' || $leaveApplication->leave_type_id == 2 ) {
            // Find the user's leave balance record
            $leaveBalance = LeaveBalance::where('user_id', $leaveApplication->user_id)->first();
            if ($leaveBalance) {
                // Add total_days of the rejected leave back to saldo_cuti
                $leaveBalance->saldo_cuti += $leaveApplication->total_days;
                $leaveBalance->save();
            }
        }

        $leaveApplication->reject($updatedBy);
        $leaveApplication->save();

        $message = 'Pengajuan cuti Dibatalkan.';
        Session::flash('successAdd', $message);
        return redirect()->route('btn-sc.cuti');

    }

    public function getPendingCount()
    {
        /** @var App\Models\User */
        $users = Auth::user();
        if ($users->hasRole('admin') || $users->hasRole('Super-Admin')) {
            // Admin and superadmin see all pending 
            $pendingCount = LeaveApplication::where('status', 'pending')->count();
        } else {
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $pendingCount = LeaveApplication::whereIn('manager_id', $subordinateIds)->where('status', 'pending')->count();
        }
        return response()->json(['pendingCount' => $pendingCount]);
    }



}
