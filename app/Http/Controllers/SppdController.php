<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class SppdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $sppds = Sppd::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
            
        // foreach ($sppds as $sppd) {
        //     if ($sppd->provinsi_tujuan) {
        //         // Fetch province name using the API
        //         $provinsiResponse = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi/' . $sppd->provinsi_tujuan);
        //         $sppd->provinsi_nama = $provinsiResponse->json()['nama'] ?? 'Unknown Provinsi';
        //     }
    
        //     if ($sppd->kota_tujuan) {
        //         // Fetch city name using the API
        //         $kotaResponse = Http::get('https://dev.farizdotid.com/api/daerahindonesia/kota/' . $sppd->kota_tujuan);
        //         $sppd->kota_nama = $kotaResponse->json()['nama'] ?? 'Unknown Kota';
        //     }
        // }
        return view('sppd.index', compact('sppds'));
    }
    

    public function approval(){
        $user = Auth::user();
        $subordinateIds = $user->karyawan->jabatan->subordinates->pluck('manager_id');
        $sppds = Sppd::whereIn('approver_id', $subordinateIds)->where('status', 'pending')->get();  
    
        return view('sppd.approval-sppd', compact('sppds','user'));
    }

    public function create()
    {
        $users = User::pluck('name', 'id'); //Select Nama Karyawan/User
        $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');
    
        // Cek apakah request ke API berhasil
        if ($response->successful()) {
            // Mengambil data provinsi dari response API
            $provinsi = $response->json()['provinsi'];
        } else {
            return back()->withErrors(['error' => 'Failed to load provinces']);
        }
        return view('sppd.create', compact('users','provinsi'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Validate form data
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'kategori_dinas' => 'required|string',
                'fasilitas_kendaraan' => 'nullable|string',
                'fasilitas_transportasi' => 'nullable',
                'fasilitas_akomodasi' => 'nullable|string',
                'provinsi_tujuan' => 'nullable',
                'lokasi_tujuan' => 'nullable',
                'biaya_transfortasi' => 'nullable|numeric',
                'biaya_akomodasi' => 'nullable|numeric',
                'biaya_pendaftaran' => 'nullable|numeric',
                'biaya_uangsaku' => 'nullable|numeric',
                'rencana_kegiatan' => 'required|string',
                'tanggal_berangkat' => 'required|date',
                'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
                
            ]);
    
            // Create new SPPD record
            $sppd = new SPPD();
            $sppd->user_id = $request->input('user_id');
            $sppd->kategori_dinas = $request->input('kategori_dinas');
            $sppd->fasilitas_kendaraan = $request->input('fasilitas_kendaraan');
            $sppd->fasilitas_transportasi = $request->input('fasilitas_transportasi');
            $sppd->fasilitas_akomodasi = $request->input('fasilitas_akomodasi');
            $sppd->provinsi_tujuan = $request->input('provinsi_tujuan');
            $sppd->lokasi_tujuan = $request->input('lokasi_tujuan');
            $sppd->biaya_transfortasi = $request->input('biaya_transfortasi');
            $sppd->biaya_akomodasi = $request->input('biaya_akomodasi');
            $sppd->biaya_pendaftaran = $request->input('biaya_pendaftaran');
            $sppd->biaya_uangsaku = $request->input('biaya_uangsaku');
            $sppd->rencana_kegiatan = $request->input('rencana_kegiatan');
            $sppd->tanggal_berangkat = $request->input('tanggal_berangkat');
            $sppd->tanggal_kembali = $request->input('tanggal_kembali');
            $sppd->level_approve = $request->input('level_approve');
            $sppd->approver_id = $request->input('approver_id');
            
            // Save the record to the database
            $sppd->save();
        // Tambahkan session flash message
        $message = 'SPPD Berhasil Ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('sppd');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the SPPD record
        $sppd = Sppd::findOrFail($id);

        // // Fetch all provinces
        // $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');
        // $provinces = $response->successful() ? $response->json()['provinsi'] : [];

        // // Fetch cities based on the selected province
        // $cities = [];
        // if ($sppd->provinsi_tujuan) {
        //     $cityResponse = Http::get("https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi={$sppd->provinsi_tujuan}");
        //     $cities = $cityResponse->successful() ? $cityResponse->json()['kota_kabupaten'] : [];
        // }

        return view('sppd.edit', compact('sppd'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validate form data
         $request->validate([
            'user_id' => 'required|exists:users,id',
            'kategori_dinas' => 'required|string',
            'fasilitas_kendaraan' => 'nullable|string',
            'fasilitas_transportasi' => 'nullable',
            'fasilitas_akomodasi' => 'nullable|string',
            'provinsi_tujuan' => 'nullable',
            'lokasi_tujuan' => 'nullable',
            'biaya_transfortasi' => 'nullable|numeric',
            'biaya_akomodasi' => 'nullable|numeric',
            'biaya_pendaftaran' => 'nullable|numeric',
            'biaya_uangsaku' => 'nullable|numeric',
            'rencana_kegiatan' => 'required|string',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            
        ]);

            $sppd = Sppd::findOrFail($id);

            $sppd->user_id = $request->input('user_id');
            $sppd->kategori_dinas = $request->input('kategori_dinas');
            $sppd->fasilitas_kendaraan = $request->input('fasilitas_kendaraan');
            $sppd->fasilitas_transportasi = $request->input('fasilitas_transportasi');
            $sppd->fasilitas_akomodasi = $request->input('fasilitas_akomodasi');
            $sppd->provinsi_tujuan = $request->input('provinsi_tujuan');
            $sppd->lokasi_tujuan = $request->input('lokasi_tujuan');
            $sppd->biaya_transfortasi = $request->input('biaya_transfortasi');
            $sppd->biaya_akomodasi = $request->input('biaya_akomodasi');
            $sppd->biaya_pendaftaran = $request->input('biaya_pendaftaran');
            $sppd->biaya_uangsaku = $request->input('biaya_uangsaku');
            $sppd->rencana_kegiatan = $request->input('rencana_kegiatan');
            $sppd->tanggal_berangkat = $request->input('tanggal_berangkat');
            $sppd->tanggal_kembali = $request->input('tanggal_kembali');
            
            // Save the record to the database
            $sppd->save();
        // Tambahkan session flash message
        $message = 'SPPD Berhasil Diubah';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('sppd');
    }

    public function approve(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;
        $sppd = Sppd::findOrFail($id);

        switch ($sppd->level_approve) {
            case 1:
                $sppd->status = 'pending';  // Status tetap 'pending' sampai persetujuan final
                $sppd->level_approve = 2;   // Naik ke level approval 2
                $sppd->approver_id = '112'; // SDM
                break;
            case 2:
                $sppd->status = 'pending';  // Masih 'pending' setelah persetujuan level 2
                $sppd->level_approve = 3;   // Naik ke level approval 3
                $sppd->approver_id = '50'; // Keuangan
                break;
            case 3:
                $sppd->status = 'approved';  // Persetujuan final di level 3
                $sppd->level_approve = 0;    // Kembali ke 0 setelah disetujui
                break;
            default:
                $sppd->status = 'pending';   // Status awal pending jika level bukan 1-3
                $sppd->level_approve = 1;    // Setel level approval awal
                $sppd->approver_id = '112';  // Setel approver ID untuk level 1
                break;
        }
        
        $sppd->approve($updatedBy);
        $sppd->save();

        $message = 'Pengajuan SPPD Di Setujui.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-sppd');

    }
    
    public function reject(Request $request, $id) {
        $user = Auth::user();
        $updatedBy = $user->name;

        $sppd = Sppd::findOrFail($id);
        // Set nilai alasan reject
        $alasan_reject = $request->input('alasan_reject');

        // Simpan alasan reject
        $sppd->alasan_reject = $alasan_reject;

        $sppd->reject($updatedBy);
        $sppd->save();

        $message = 'Pengajuan SPPD Tidak Di Setujui.';
        Session::flash('successAdd', $message);
        return redirect()->route('approval-sppd');

    }

    public function riwayat()
    {
        $user = Auth::user();
        $sppds = Sppd::where('user_id', $user->id)
            ->whereIn('status', ['approved','rejected'])
            ->get();
    
        return view('sppd.riwayat', compact('sppds','user'));
    }

    public function laporan(string $id)
    {
        //
    }

    public function search(string $id)
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
