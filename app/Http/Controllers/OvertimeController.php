<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Overtime;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\OvertimeSearchRequest;
use App\Traits\ApprovalCountTrait;
use App\Notifications\OvertimeNotification;
use App\Models\OvertimeApprovalHistory;

class OvertimeController extends Controller
{
    use ApprovalCountTrait;

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

        // Ambil pengajuan lembur yang diajukan oleh pengguna yang sedang login
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
                // Query untuk mendapatkan pengajuan lembur yang memiliki unit yang sama dengan unit pengguna
               
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $overtimes = Overtime::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->get();   
        
            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan lembur yang diajukan oleh pengguna
                $overtimes = $users->overtimes()->where('status', 'pending')->get();
            }
    
            return view('overtime.approval-overtime', compact('overtimes'));   
            }
            abort(401);
    }


    public function riwayat()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
    
        // Ambil pengajuan lembur yang diajukan oleh pengguna yang sedang login
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
        if (empty($approver_id)) {
            $selectedUser = User::findOrFail($request->input('user_id'));
            
            if (!$selectedUser->karyawan || !$selectedUser->karyawan->jabatan) {
                return redirect()->back()->withErrors([
                    'approver_id' => 'Data jabatan karyawan tidak ditemukan.'
                ]);
            }
            $approver_id = $selectedUser->karyawan->jabatan->manager_id;
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
        Session::flash('successAdd', 'Pengajuan Lembur berhasil dibuat.');
        
        $this->notifyManager($overtimes, $approver_id);
        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('overtime');
    }

    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->karyawan->name;
        $overtime = Overtime::findOrFail($id);

        if ($overtime->level_approve === 1) {
            $overtime->status = 'approved';
            $overtime->level_approve = 0;

            OvertimeApprovalHistory::create([
                'overtime_id' => $overtime->id,
                'user_id' => $user->id,
                'action' => 'approved',
                'level_approve' => 1,
            ]);
            $this->notifyEmployee($overtime, 'approved', $updatedBy);
        } elseif ($overtime->level_approve === 2) {
            $nextJabatanId = $user->karyawan->jabatan->manager_id;

            $overtime->approver_id = $nextJabatanId;
            $overtime->level_approve = 1;
            $overtime->updated_by_atasan = $updatedBy;
            $overtime->updated_at_atasan = now();

            OvertimeApprovalHistory::create([
                'overtime_id' => $overtime->id,
                'user_id' => $user->id,
                'action' => 'escalated',
                'level_approve' => 2,
            ]);
            $this->notifyEscalation($overtime, $nextJabatanId, $updatedBy);
        }
        
        $overtime->approve($updatedBy);
        $overtime->save();

        Session::flash('successAdd', 'Pengajuan Lembur Disetujui.');
        return redirect()->route('approval-overtime');

    }
    
    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->karyawan->name;

        $overime = Overtime::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');
        $overime->alasan_reject = $alasan_reject;

            OvertimeApprovalHistory::create([
                'overtime_id' => $overime->id,
                'user_id' => $user->id,
                'action' => 'rejected',
                'level_approve' => $overime->level_approve,
            ]);
        $overime->reject($updatedBy);
        $overime->save();

        Session::flash('successAdd', 'Pengajuan Lembur Tidak DiSetujui.');
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
    
    public function searchlembur(User $user) {
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
        return view('overtime.searchlembur', compact('users'));
    }

    public function searchapprove(Request $request) {
        $users = $request->input('user_id');
        $startDate = $request->input('start_date');

        $query = Overtime::Q_overtime()
            ->where('overtimes.status', 'approved');

            if ($users) {
                $query->where('users.id', $users);
            }
        
            if ($startDate) {
                $query->whereRaw('DATE(overtimes.start_date) = ?', [$startDate]);
            }
        
            $results = $query->get();

        return view('overtime.results_approve', compact('results'));
    }


    public function laporan()
    {
        return view('overtime.search');
    }

    public function search(OvertimeSearchRequest $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        ReportHistory::create([
            'user_id' => Auth::id(), // Jika user login
            'start_date' => $startDate,
            'end_date' => $endDate,
            'ip_address' => $request->ip(),
            'name' => 'Pengajuan Lembur'    
        ]);

        $query = Overtime::Q_overtime()
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
        $jumlah = $this->getPendingCountForUser(Overtime::class, 'approver_id');
        return response()->json(['countOvertime' => $jumlah]);
    }
    
    public function report_history_lembur(){
        $reporthistory = ReportHistory::with('user')->where('name','Pengajuan Lembur')->orderBy('created_at', 'desc')->get();
        return view('overtime.report-history', compact('reporthistory'));
    }

     private function notifyManager(Overtime $overtime, int $jabatanId): void
    {
        // Cari user yang memiliki jabatan dengan ID = $jabatanId
        $manager = User::whereHas('karyawan', function($query) use ($jabatanId) {
            $query->where('jabatan_id', $jabatanId)->where('status', 'active'); // Pastikan hanya mencari user dengan status aktif
        })->first();
        
        if (!$manager) return;
        
        $employee = User::find($overtime->user_id);
    
        $notification = new OvertimeNotification('submitted', [
            'overtime_id'     => $overtime->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'start_date'    => Carbon::parse($overtime->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($overtime->end_date)->format('d M Y'),
            'interval'      => $overtime->interval,
        ]);
        
        $manager->notify($notification);
    }
    
    private function notifyEmployee(Overtime $overtime,string $status,string $actorName,string $reason = ''): void {
        $employee = User::find($overtime->user_id);

        if (!$employee) return;

        $payload = [
            'overtime_id'   => $overtime->id,
            'start_date' => Carbon::parse($overtime->start_date)->format('d M Y'),
            'end_date'   => Carbon::parse($overtime->end_date)->format('d M Y'),
            'interval'   => $overtime->interval,
        ];

        if ($status === 'approved') {
            $payload['approved_by'] = $actorName;
        } else {
            $payload['rejected_by'] = $actorName;
            $payload['reason']      = $reason;
        }

        $employee->notify(new OvertimeNotification($status, $payload));
    }


    private function notifyEscalation(Overtime $overtime,int $nextJabatanId, string $escalatedBy): void {
        // Sama persis dengan logika notifyManager()
        $nextManager = User::whereHas('karyawan', function ($query) use ($nextJabatanId) {
            $query->where('jabatan_id', $nextJabatanId)
                    ->where('status', 'active'); 
        })->first();

        if (!$nextManager) return;

        $employee = User::find($overtime->user_id);

        $nextManager->notify(new OvertimeNotification('escalated', [
            'overtime_id'      => $overtime->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'start_date'    => Carbon::parse($overtime->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($overtime->end_date)->format('d M Y'),
            'interval'    => $overtime->interval,
            'escalated_by'  => $escalatedBy,
        ]));
    }
    
    public function historyApproval(){
        $user = Auth::user();
        $approvalHistory = OvertimeApprovalHistory::with('overtime.user.karyawan')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('overtime.history_approval', compact('approvalHistory'));
    }

}
