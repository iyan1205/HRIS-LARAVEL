<?php

namespace App\Http\Controllers;

use App\Models\BenefitKartap;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BenefitKartapController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $tahun = $request->input('tahun', now()->year);
        $level = $user->karyawan->jabatan->level;

        $benefitKartaps = BenefitKartap::orderBy('created_at', 'desc')
            ->where('user_id', $user->id)
            ->get();

        $rekapPlafon = BenefitKartap::rekapSemuaBenefit($user->id, $level, $tahun);

        return view('benefit-kartap.index', compact('benefitKartaps', 'rekapPlafon', 'tahun'));
    }

    public function approvalIndex()
    {
        $user = auth()->user();
         $query = BenefitKartap::with('user')
                ->latest();

        if ($user->karyawan->jabatan->id == 112) {
            // SPV SDM
            $query->where('status', 'pending');

        } elseif ($user->karyawan->jabatan->id == 6) {
            // Manajer SDM
            $query->where('status', 'approval_1');

        } elseif ($user->karyawan->jabatan->id == 50) {
            // Manajer Keuangan
            $query->where('status', 'approval_2');

        } else {
            // Bukan approver
            $query->whereRaw('1 = 0');
        }

        $benefitKartaps = $query->get();

        return view('benefit-kartap.approval-index', compact('benefitKartaps'));
    }

    public function syarat()
    {
        return view('benefit-kartap.syarat');
    }


    public function create()
    {
        $user = auth()->user();
        return view('benefit-kartap.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
            'jenis_benefit' => 'required|string|max:255',
            'form_pengajuan' => 'required|file|mimes:pdf|max:2048',
            'resume' => 'required|file|mimes:pdf|max:2048',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $benefitKartap = new BenefitKartap();
        $benefitKartap->user_id = auth()->id();
        $benefitKartap->nominal = $request->nominal;
        $benefitKartap->jenis_benefit = $request->jenis_benefit;
        

        if ($request->hasFile('form_pengajuan')) {
            $formPengajuanPath = $request->file('form_pengajuan')->store('benefit_kartap_forms', 'public');
            $benefitKartap->form_pengajuan = $formPengajuanPath;
        }
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('benefit_kartap_resumes', 'public');
            $benefitKartap->resume = $resumePath;
        }
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPembayaranPath = $request->file('bukti_pembayaran')->store('benefit_kartap_bukti', 'public');
            $benefitKartap->bukti_pembayaran = $buktiPembayaranPath;
        }

       $spvSdm = User::whereHas('karyawan', function ($q) {
            $q->where('jabatan_id', 112);
        })->first();

        $managerSdm = User::whereHas('karyawan', function ($q) {
            $q->where('jabatan_id', 6);
        })->first();

        $managerKeuangan = User::whereHas('karyawan', function ($q) {
            $q->where('jabatan_id', 50);
        })->first();

        $benefitKartap->approval_1_by = $spvSdm?->id;
        $benefitKartap->approval_2_by = $managerSdm?->id;
        $benefitKartap->approved_by  = $managerKeuangan?->id;
        $benefitKartap->save();
        

        return redirect()->route('benefit-kartap.index')->with('successAdd', 'Pengajuan Benefit Kartap berhasil dibuat.');
    }

    public function show($id)
    {
        // Logic to display a specific benefit kartap record
    }

    public function edit($id)
    {
        $benefitKartap = BenefitKartap::findOrFail($id);
        return view('benefit-kartap.edit', compact('benefitKartap'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
            'jenis_benefit' => 'required|string|max:255',
            'form_pengajuan' => 'nullable|file|mimes:pdf|max:2048',
            'resume' => 'nullable|file|mimes:pdf|max:2048',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $benefitKartap = BenefitKartap::findOrFail($id);
        $benefitKartap->nominal = $request->nominal;
        $benefitKartap->jenis_benefit = $request->jenis_benefit;

        if ($request->hasFile('form_pengajuan')) {
            $formPengajuanPath = $request->file('form_pengajuan')->store('benefit_kartap_forms', 'public');
            $benefitKartap->form_pengajuan = $formPengajuanPath;
        }
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('benefit_kartap_resumes', 'public');
            $benefitKartap->resume = $resumePath;
        }
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPembayaranPath = $request->file('bukti_pembayaran')->store('benefit_kartap_bukti', 'public');
            $benefitKartap->bukti_pembayaran = $buktiPembayaranPath;
        }
        $benefitKartap->status = 'pending'; // Reset status to pending on update
        $benefitKartap->save();

        return redirect()->route('benefit-kartap.index')->with('successAdd', 'Pengajuan Benefit Kartap berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Logic to delete a specific benefit kartap record
    }

    public function approve($id)
    {
        $benefitKartap = BenefitKartap::findOrFail($id);
        $user = auth()->user();

        if ($user->id === $benefitKartap->approval_1_by && $benefitKartap->status === 'pending') {
            $benefitKartap->status = 'approval_1';
            $benefitKartap->approval_1_at = now();
        } elseif ($user->id === $benefitKartap->approval_2_by && $benefitKartap->status === 'approval_1') {
            $benefitKartap->status = 'approval_2';
            $benefitKartap->approval_2_at = now();
        } elseif ($user->id === $benefitKartap->approved_by && $benefitKartap->status === 'approval_2') {
            $benefitKartap->status = 'approved';
            $benefitKartap->approved_at = now();
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui pengajuan ini.');
        }

        $benefitKartap->save();

        return redirect()->back()->with('successAdd', 'Pengajuan Benefit Kartap berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $benefitKartap = BenefitKartap::findOrFail($id);
        $user = auth()->user();

        $benefitKartap->alasan_reject = $request->input('alasan_reject');
        $benefitKartap->status = 'rejected';

        $benefitKartap->save();

        return redirect()->back()->with('successAdd', 'Pengajuan Benefit Kartap berhasil ditolak.');
    }


}
