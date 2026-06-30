<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\OnCall;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\OncallSearchRequest;
use App\Traits\ApprovalCountTrait;
use App\Notifications\OncallNotification;
use App\Models\OncallApprovalHistory;

class OnCallController extends Controller
{
    use ApprovalCountTrait;

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
                // Query untuk mendapatkan pengajuan
               
            $subordinateIds = $users->karyawan->jabatan->subordinates->pluck('manager_id');
            $oncalls = OnCall::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->get();   
        
            } else {
                // Jika pengguna bukan 'Super-Admin', 'admin', atau 'Approver', ambil pengajuan oncall yang diajukan oleh pengguna
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
    
        // Ambil pengajuan oncall yang diajukan oleh pengguna yang sedang login
        $oncalls = OnCall::where('user_id', $user->id)
            ->whereIn('status', ['rejected', 'approved','canceled'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('oncall.riwayat', compact('oncalls','user'));
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
            'approver_id' => 'nullable',
            'level_approve' => 'nullable'
            
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        // Ambil nilai approver_id dari user_id 
        $approver_id = $request->input('approver_id');
        if (empty($approver_id)) {
            $selectedUser = User::findOrFail($request->input('user_id'));
            
            if (!$selectedUser->karyawan || !$selectedUser->karyawan->jabatan) {
                return redirect()->back()->withErrors([
                    'approver_id' => 'Data jabatan karyawan tidak ditemukan.'
                ]);
            }
            
            // Ambil manager_id dari jabatan, simpan ke kolom approver_id di on_calls
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
        $oncalls = OnCall::create([
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
        Session::flash('successAdd', 'Pengajuan On Call berhasil dibuat.');

        $this->notifyManager($oncalls, $approver_id);
        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('oncall');
    }

    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->karyawan->name;
        $oncall = OnCall::findOrFail($id);

        if ($oncall->level_approve === 1) {
            $oncall->status = 'approved';
            $oncall->level_approve = 0;
            
            OncallApprovalHistory::create([
                'oncall_id' => $oncall->id,
                'user_id' => $user->id,
                'action' => 'approved',
                'level_approve' => 1,             
            ]);

            $this->notifyEmployee($oncall,'approved',$updatedBy);
        } elseif ($oncall->level_approve === 2) {
            $nextJabatanId = $user->karyawan->jabatan->manager_id;

            $oncall->approver_id = $nextJabatanId;
            $oncall->level_approve = 1;
            $oncall->updated_by_atasan = $updatedBy;
            $oncall->updated_at_atasan = now();

            OncallApprovalHistory::create([
                'oncall_id' => $oncall->id,
                'user_id' => $user->id,
                'action' => 'escalated',
                'level_approve' => 2,
            ]);
            $this->notifyEscalation($oncall,$nextJabatanId, $updatedBy);
        }
        

        $oncall->approve($updatedBy);
        $oncall->save();

        Session::flash('successAdd', 'Pengajuan Oncall DiSetujui.');
        return redirect()->route('approval-oncall');

    }
    
    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->karyawan->name;

        $oncall = OnCall::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');
        $oncall->alasan_reject = $alasan_reject;

        OncallApprovalHistory::create([
            'oncall_id' => $oncall->id,
            'user_id' => $user->id,
            'action' => 'rejected',
            'level_approve' => $oncall->level_approve, // Simpan level
            'approved_by' => $updatedBy,
        ]);

        $oncall->reject($updatedBy);
        $oncall->save();

        $this->notifyEmployee($oncall,'rejected',$updatedBy,$alasan_reject);

        Session::flash('successAdd', 'Pengajuan Oncall Tidak Di Setujui.');
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
        $oncalls = OnCall::find($id);
        return view('oncall.edit', compact('oncalls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validasi input dari form
         $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'keterangan' => 'required'
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

        // Buat dan simpan jabatan baru
        $oncalls = OnCall::findOrFail($id);
        $oncalls->update([
            'user_id' => $request->input('user_id'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'interval' => $interval_formatted,
            'level_approve' => $request->input('level_approve'),
            'keterangan' => $request->input('keterangan'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Pengajuan On Call berhasil Di Edit.';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('oncall');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchoncall(User $user) {
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
        return view('oncall.searchoncall', compact('users'));
    }

    public function searchapprove(Request $request) {
        $users = $request->input('user_id');
        $startDate = $request->input('start_date');

        $query = OnCall::Q_oncall()
        ->where('on_calls.status', 'approved');
        if ($users) {
            $query->where('users.id', $users);
        }
    
        if ($startDate) {
            $query->whereRaw('DATE(on_calls.start_date) = ?', [$startDate]);
        }
    
        $results = $query->get();

        return view('oncall.results_approve', compact('results'));
    }

    public function laporan()
    {
        return view('oncall.search');
    }

    public function search(OncallSearchRequest $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        ReportHistory::create([
            'user_id' => Auth::id(), // Jika user login
            'start_date' => $startDate,
            'end_date' => $endDate,
            'ip_address' => $request->ip(),
            'name' => 'Pengajuan OnCall'    
        ]);

        $query = OnCall::Q_oncall()
            ->whereBetween('on_calls.start_date', [$startDate, $endDate])
            ->whereBetween('on_calls.end_date', [$startDate, $endDate]);

            if ($status) {
                $query->where('on_calls.status', $status);
            }
        
            $results = $query->get();

        return view('oncall.search_results', compact('results', 'status'));
    }

    public function getOncallCount()
    {
        $jumlah = $this->getPendingCountForUser(OnCall::class, 'approver_id');
        return response()->json(['countOncall' => $jumlah]);
    }

    
    public function report_history_oncall(){
        $reporthistory = ReportHistory::with('user')->where('name','Pengajuan OnCall')->orderBy('created_at', 'desc')->get();
        return view('oncall.report-history', compact('reporthistory'));
    }

    private function notifyManager(Oncall $oncall, int $jabatanId): void
    {
        // Cari user yang memiliki jabatan dengan ID = $jabatanId
        $manager = User::whereHas('karyawan', function($query) use ($jabatanId) {
            $query->where('jabatan_id', $jabatanId)->where('status', 'active'); // Pastikan hanya mencari user dengan status aktif
        })->first();
        
        if (!$manager) return;
        
        $employee = User::find($oncall->user_id);
    
        $notification = new OncallNotification('submitted', [
            'oncall_id'     => $oncall->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'start_date'    => Carbon::parse($oncall->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($oncall->end_date)->format('d M Y'),
            'interval'      => $oncall->interval,
        ]);
        
        $manager->notify($notification);
    }
    
    private function notifyEmployee(Oncall $oncall,string $status,string $actorName,string $reason = ''): void {
        $employee = User::find($oncall->user_id);

        if (!$employee) return;

        $payload = [
            'oncall_id'   => $oncall->id,
            'start_date' => Carbon::parse($oncall->start_date)->format('d M Y'),
            'end_date'   => Carbon::parse($oncall->end_date)->format('d M Y'),
            'interval'   => $oncall->interval,
        ];

        if ($status === 'approved') {
            $payload['approved_by'] = $actorName;
        } else {
            $payload['rejected_by'] = $actorName;
            $payload['reason']      = $reason;
        }

        $employee->notify(new OncallNotification($status, $payload));
    }


    private function notifyEscalation(Oncall $oncall,int $nextJabatanId, string $escalatedBy): void {
        // Sama persis dengan logika notifyManager()
        $nextManager = User::whereHas('karyawan', function ($query) use ($nextJabatanId) {
            $query->where('jabatan_id', $nextJabatanId)
                    ->where('status', 'active'); 
        })->first();

        if (!$nextManager) return;

        $employee = User::find($oncall->user_id);

        $nextManager->notify(new OncallNotification('escalated', [
            'oncall_id'      => $oncall->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'start_date'    => Carbon::parse($oncall->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($oncall->end_date)->format('d M Y'),
            'interval'    => $oncall->interval,
            'escalated_by'  => $escalatedBy,
        ]));
    }

    public function historyApproval(){
        $user = Auth::user();
        $approvalHistory = OncallApprovalHistory::with('oncall.user.karyawan')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('oncall.history_approval', compact('approvalHistory'));
    }
}
