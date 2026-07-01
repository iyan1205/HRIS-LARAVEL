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
use ZipArchive;
use App\Http\Requests\LeaveSearchRequest;
use App\Traits\ApprovalCountTrait;
use App\Notifications\LeaveNotification;
use App\Models\LeaveApprovalHistory;

class LeaveApplicationController extends Controller
{
    use ApprovalCountTrait;

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
    // Halaman Approval Cuti
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
        /* ── Validasi Dasar ── */
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'manager_id'    => 'nullable',
            'level_approve' => 'nullable|integer',
            'file_upload'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /* ── Cek Pending ── */
        if (LeaveApplication::where('user_id', $request->user_id)->where('status', 'pending')->exists()) {
            return back()->withInput()->with('error', 'Pengajuan sebelumnya belum disetujui.');
        }

        /* ── Leave Type ── */
        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        /* ── Hitung Hari ── */
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->startOfDay();
        $totalDays = $startDate->diffInDays($endDate) + 1;

        /* ── Validasi Max ── */
        if (!empty($leaveType->max_amount) && $leaveType->max_amount > 0 && $totalDays > $leaveType->max_amount) {
            return back()->withInput()->with('error',
                'Jumlah hari cuti melebihi batas maksimal (' . $leaveType->max_amount . ' hari).');
        }

        /* ── Validasi Saldo ── */
        if ($leaveType->cek_saldo == 0) {
            $leaveBalance = LeaveBalance::where('user_id', $request->user_id)->first();
            if (!$leaveBalance || $leaveBalance->saldo_cuti <= 0) {
                return back()->withInput()->with('error', 'Sisa cuti sudah habis.');
            }
        }

        /* ── File Upload ── */
        $filePath = null;
        if ($leaveType->file_upload === 'yes') {
            if (!$request->hasFile('file_upload')) {
                return back()->withInput()->with('error', 'Jenis cuti ini mewajibkan upload dokumen.');
            }
            $file     = $request->file('file_upload');
            $fileName = now()->format('Ymd') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('file_cuti', $fileName, 'public');
        }

        /* ── Manager ── */
        $managerId = $request->manager_id;
        if (empty($managerId)) {
            $managerId = User::with('karyawan.jabatan')->findOrFail($request->user_id)
                ->karyawan->jabatan->manager_id;
        }

        /* ── Simpan ── */
        $leaveApplication = LeaveApplication::create([
            'user_id'       => $request->user_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'total_days'    => $totalDays,
            'manager_id'    => $managerId,
            'level_approve' => $request->level_approve,
            'file_upload'   => $filePath,
            'status'        => 'pending',
        ]);

        /* ── Notifikasi → Manager ── */
        // ✅ Cast ke int: $request->manager_id / jabatan->manager_id bisa bertipe string
        //    notifyManager(int $managerId) → TypeError jika tidak di-cast → notif gagal
        $this->notifyManager($leaveApplication, (int) $managerId);

        return redirect()->route('pengajuan-cuti')->with('successAdd', 'Pengajuan cuti berhasil dibuat.');
    }

    
    /* ══════════════════════════════════════════════════
     |  APPROVE
     ══════════════════════════════════════════════════ */
    public function approve(Request $request, $id)
    {
        $user             = Auth::user();
        $updatedBy        = $user->karyawan->name;
        $leaveApplication = LeaveApplication::with('leaveType')->findOrFail($id);
 
        /* ── LEVEL 1 — Approval final ── */
        if ($leaveApplication->level_approve == 1) {
 
            if ($leaveApplication->leaveType->saldo_cuti !== 'no') {
                $leaveBalance = LeaveBalance::where('user_id', $leaveApplication->user_id)
                    ->firstOrFail();
                $leaveBalance->saldo_cuti -= $leaveApplication->total_days;
                $leaveBalance->save();
            }
 
            $leaveApplication->status        = 'approved';
            $leaveApplication->level_approve = 0;

            // Simpan riwayat
            LeaveApprovalHistory::create([
                'leave_application_id' => $leaveApplication->id,
                'user_id'              => $user->id,
                'action'               => 'approved',
                'level_approve'        => 1,
            ]);

            $this->notifyEmployee($leaveApplication, 'approved', $updatedBy);
 
        /* ── LEVEL 2 — Eskalasi ke atasan berikutnya ── */
        } elseif ($leaveApplication->level_approve == 2) {
 
            $nextJabatanId = (int) $user->karyawan->jabatan->manager_id;
 
            $leaveApplication->manager_id        = $nextJabatanId;
            $leaveApplication->level_approve     = 1;
            $leaveApplication->updated_by_atasan = $updatedBy;
            $leaveApplication->updated_at_atasan = now();
            
            // Simpan riwayat
            LeaveApprovalHistory::create([
                'leave_application_id' => $leaveApplication->id,
                'user_id'              => $user->id,
                'action'               => 'escalated',
                'level_approve'        => 2,
            ]);

            $this->notifyEscalation($leaveApplication, $nextJabatanId, $updatedBy);
        }
 
        $leaveApplication->approve($updatedBy);
        $leaveApplication->save();
 
        Session::flash('successAdd', 'Pengajuan Cuti Di Setujui.');
        return redirect()->route('approval-cuti');
    }
    

    public function cancel(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->karyawan->name;
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

        $alasan_reject = $request->input('alasan_reject');
        $leaveApplication->alasan_reject = $alasan_reject;

        LeaveApprovalHistory::create([
                'leave_application_id' => $leaveApplication->id,
                'user_id'              => $user->id,
                'action'               => 'rejected',
                'level_approve'        => $leaveApplication->level_approve,
            ]);

        $leaveApplication->reject($updatedBy);
        $leaveApplication->save();
        /* ── ✅ NOTIFIKASI: Karyawan — cuti ditolak ── */
        $this->notifyEmployee(
            $leaveApplication,
            'rejected',
            $user->karyawan->name,
            $request->input('reason', '')
        );

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
                $message = 'File terlalu besar atau format tidak didukung.';
                return redirect()->back()->withInput()->with('error', $message);
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
    
        Session::flash('successAdd', 'Pengajuan cuti Tidak Disetujui.');
    
        return redirect()->route('pengajuan-cuti');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //Menu Cancel Cuti
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
    // Hasil Pencarian Menu Cancel Cuti
    public function searchapprove(Request $request){
        $users = $request->input('user_id');
        $startDate = $request->input('start_date');

        $query = LeaveApplication::Q_approve()
            ->where('leave_applications.status', 'approved');
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

    public function search(LeaveSearchRequest $request){
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

        $query = LeaveApplication::Q_laporan()
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
        $jumlah = $this->getPendingCountForUser(LeaveApplication::class, 'manager_id');
        return response()->json(['pendingCount' => $jumlah]);
    }

    public function report_history_cuti(){
        $reporthistory = ReportHistory::with('user')->where('name','Pengajuan Cuti')->orderBy('created_at', 'desc')->get();

        return view('cuti.report-history', compact('reporthistory'));
    }

    public function file_cuti(User $user){
        $users = $user->activeKaryawan()
                ->get()
                ->sortBy(fn($user) => $user->karyawan->name)
                ->mapWithKeys(fn($user) => [$user->id => $user->karyawan->name]);
        return view('cuti.file.file_cuti', compact('users'));
    }

    public function search_file(Request $request){
        $users    = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = LeaveApplication::Q_approve()
            ->where('leave_applications.status', 'approved')
            ->where('leave_types.file_upload','yes');

        if (!empty($users)) {
            $query->where('users.id', $users);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('leave_applications.start_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('leave_applications.start_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('leave_applications.end_date', '<=', $endDate);
        }

        $results = $query->get();

        return view('cuti.file.results_file', compact('results', 'users'));
    }

    public function downloadFile($id)
    {
            $application = LeaveApplication::findOrFail($id);

            if (!$application->file_upload) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            // Path fisik di server
            $filePath = storage_path('app/public/' . $application->file_upload);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ada di server.');
            }

            // Paksa download
            return response()->download($filePath, basename($filePath));
    }

    public function downloadAllByFilter(Request $request)
    {
        $users     = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = LeaveApplication::with('karyawan')
            ->where('status', 'approved')
            ->whereNotNull('file_upload');

        if (!empty($users)) {
            $query->where('user_id', $users);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('start_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        $applications = $query->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'Tidak ada file cuti sesuai filter.');
        }

        $zipFileName = 'file_cuti_filter_' . now()->format('Ymd_His') . '.zip';
        $zip = new \ZipArchive;
        $tmpFile = tempnam(sys_get_temp_dir(), $zipFileName);

        if ($zip->open($tmpFile, \ZipArchive::CREATE) === TRUE) {
            foreach ($applications as $app) {
                $filePath = storage_path('app/public/' . $app->file_upload);
                if (file_exists($filePath)) {
                    $karyawanName = $app->karyawan->name ?? 'unknown';
                    $zip->addFile($filePath, $karyawanName . '_' . basename($filePath));
                }
            }
            $zip->close();
        }

        return response()->download($tmpFile, $zipFileName)->deleteFileAfterSend(true);
    }

    /* ══════════════════════════════════════════════════
     |  MARK AS READ — Tandai notifikasi telah dibaca
     ══════════════════════════════════════════════════ */
    public function markNotificationRead(Request $request, $notificationId)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /* ══════════════════════════════════════════════════
     |  MARK ALL READ — Tandai semua notifikasi dibaca
     ══════════════════════════════════════════════════ */
    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /* ══════════════════════════════════════════════════
     |  GET NOTIFICATIONS — Untuk dropdown bell (JSON)
     ══════════════════════════════════════════════════ */
    public function getNotifications()
    {
        $user          = Auth::user();
        $notifications = $user->unreadNotifications()->latest()->take(10)->get();

        $data = $notifications->map(function ($n) {
            return [
                'id'          => $n->id,
                'title'       => $n->data['title']   ?? '',
                'message'     => $n->data['message']  ?? '',
                'url'         => $n->data['url']      ?? '#',
                'icon'        => $n->data['icon']     ?? 'bell',
                'color'       => $n->data['color']    ?? 'gray',
                'is_read'     => false,
                'time'        => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications' => $data,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    /* ══════════════════════════════════════════════════
     |  PRIVATE HELPERS
     ══════════════════════════════════════════════════ */

    /**
     * Kirim notifikasi ke manager saat karyawan submit cuti.
     */
    private function notifyManager(LeaveApplication $leave, int $jabatanId): void
    {
        // Cari user yang memiliki jabatan dengan ID = $jabatanId
        $manager = User::whereHas('karyawan', function($query) use ($jabatanId) {
            $query->where('jabatan_id', $jabatanId)->where('status', 'active'); // Pastikan hanya mencari user dengan status aktif
        })->first();
        
        if (!$manager) return;
        
        $employee = User::find($leave->user_id);
    
        $notification = new LeaveNotification('submitted', [
            'leave_id'      => $leave->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'leave_type'    => $leave->leaveType->kategori_cuti ?? '-',
            'start_date'    => Carbon::parse($leave->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($leave->end_date)->format('d M Y'),
            'total_days'    => $leave->total_days,
        ]);
        
        $manager->notify($notification);
    }
    
    /**
     * Kirim notifikasi ke karyawan (approved / rejected).
     */
    private function notifyEmployee(LeaveApplication $leave,string $status,string $actorName,string $reason = ''): void {
        $employee = User::find($leave->user_id);

        if (!$employee) return;

        $payload = [
            'leave_id'   => $leave->id,
            'leave_type' => $leave->leaveType->kategori_cuti ?? '-',
            'start_date' => Carbon::parse($leave->start_date)->format('d M Y'),
            'end_date'   => Carbon::parse($leave->end_date)->format('d M Y'),
        ];

        if ($status === 'approved') {
            $payload['approved_by'] = $actorName;
        } else {
            $payload['rejected_by'] = $actorName;
            $payload['reason']      = $reason;
        }

        $employee->notify(new LeaveNotification($status, $payload));
    }

    /**
     * Kirim notifikasi ke manager berikutnya saat cuti dieskalasi.
     */

    private function notifyEscalation(LeaveApplication $leave,int $nextJabatanId, string $escalatedBy): void {
        // Sama persis dengan logika notifyManager()
        $nextManager = User::whereHas('karyawan', function ($query) use ($nextJabatanId) {
            $query->where('jabatan_id', $nextJabatanId)
                    ->where('status', 'active'); 
        })->first();

        if (!$nextManager) return;

        $employee = User::find($leave->user_id);

        $nextManager->notify(new LeaveNotification('escalated', [
            'leave_id'      => $leave->id,
            'employee_name' => $employee?->karyawan?->name ?? 'Karyawan',
            'leave_type'    => $leave->leaveType->kategori_cuti ?? '-',
            'start_date'    => Carbon::parse($leave->start_date)->format('d M Y'),
            'end_date'      => Carbon::parse($leave->end_date)->format('d M Y'),
            'total_days'    => $leave->total_days,
            'escalated_by'  => $escalatedBy,
        ]));
    }

    public function historyApproval()
    {
        $user = Auth::user();

        $approvalHistory = LeaveApprovalHistory::with([
                'leaveApplication.user.karyawan.jabatan'
            ])
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cuti.history_approval', compact('approvalHistory', 'user'));
    }

}
