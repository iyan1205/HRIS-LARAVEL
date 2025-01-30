<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\LeaveBalance;
use App\Models\Pelatihan;
use App\Models\Pendidikan;
use App\Models\ResignReason;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view karyawan', ['only' => ['index','resign']]);
        $this->middleware('permission:create karyawan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit karyawan', ['only' => ['edit','update']]);
        $this->middleware('permission:delete karyawan', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $karyawans = Karyawan::where('status', 'active')
        ->orderBy('nik', 'desc')
        ->with('pelatihans')
        ->get();
        $jumlahKaryawanAktif = Karyawan::where('status', 'active')->count();
        return view('karyawan.index', compact('karyawans', 'jumlahKaryawanAktif'));
    }

    public function resign()
    {
        $resigns = Karyawan::where('status', 'resign')
        ->orderBy('tgl_resign', 'asc')
        ->get();
        return \view('karyawan.resign', compact('resigns'));
    }

    public function create()
    {
        $users = User::pluck('name', 'id');
        $departemens = Departemen::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');
        $jabatans = Jabatan::pluck('name', 'id');
        $pelatihans = Pelatihan::all();
        return view('karyawan.create', compact( 'users', 'departemens', 'units', 'jabatans','pelatihans'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nik' => 'required|numeric|unique:karyawans,nik|digits:4|min:4',
            'user_id' => 'required',
            'departemen_id' => 'required',
            'jabatan_id' => 'required',
            'unit_id' => 'required',
            'nomer_ktp' => 'nullable',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'nullable',
            'alamat_ktp' => 'nullable',
            'gender' => 'nullable',
            'institusi' => 'nullable',
            'pendidikan_terakhir' => 'nullable',
            'tahun_lulus' => 'nullable',
            'status_ktp' => 'required',
            'telepon' => 'required',
            'npwp' => 'required',
            'status_karyawan' => 'required', //kontrak_atau_tetap
            'kontrak.*.tanggal_mulai' => 'required|date', // Validasi array kontrak
            'kontrak.*.tanggal_selesai' => 'required|date|after_or_equal:kontrak.*.tanggal_mulai',
            'kontrak.*.deskripsi_kontrak' => 'nullable|string|max:255',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    

        // Buat dan simpan karyawan baru
        $karyawan = Karyawan::create([
            'name' => $request->input('name'),
            'nik' => $request->input('nik'),
            'status_karyawan' => $request->input('status_karyawan'),
            'nomer_ktp' => $request->input('nomer_ktp'),
            'user_id' => $request->input('user_id'),
            'departemen_id' => $request->input('departemen_id'),
            'jabatan_id' => $request->input('jabatan_id'),
            'unit_id' => $request->input('unit_id'),
            'alamat_ktp' => $request->input('alamat_ktp'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'gender' => $request->input('gender'),
            'telepon' => $request->input('telepon'),
            'status_ktp' => $request->input('status_ktp'),
            'npwp' => $request->input('npwp'),
           
        ]);

        $pendidikan = new Pendidikan([
            'institusi' => $request->input('institusi'),
            'pendidikan_terakhir' => $request->input('pendidikan_terakhir'),
            'tahun_lulus' => $request->input('tahun_lulus'),
            'nomer_ijazah' => $request->input('nomer_ijazah'),
            'nomer_str' => $request->input('nomer_str'),
            'exp_str' => $request->input('exp_str'),
            'profesi' => $request->input('profesi'),
            'cert_profesi' => $request->input('cert_profesi'),
            'nomer_sip' => $request->input('nomer_sip'),
            'tgl_terbit_sip' => $request->input('tgl_terbit_sip'),
            'exp_sip' => $request->input('exp_sip'),
        ]);

        $karyawan->pendidikan()->save($pendidikan);

        // Menambahkan pelatihan karyawan
        if ($request->has('pelatihan')) {
            $karyawan->pelatihans()->attach($request->pelatihan);
        }

        // Tambahkan kontrak karyawan
        if ($request->has('kontrak')) {
            foreach ($request->input('kontrak') as $kontrakData) {
                $karyawan->kontrak()->create([
                    'tanggal_mulai' => $kontrakData['tanggal_mulai'],
                    'tanggal_selesai' => $kontrakData['tanggal_selesai'],
                    'deskripsi_kontrak' => $kontrakData['deskripsi_kontrak'],
                ]);
            }
        }

        // Tambahkan session flash message
        $message = 'Karyawan berhasil ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('karyawan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //   
    }

    public function edit(Request $request, $id)
    {
        $karyawan = Karyawan::with('pendidikan' , 'kontrak')->findOrFail($id);
        $pelatihans = Pelatihan::all();
        $users = User::pluck('name', 'id');
        $departemens = Departemen::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');
        $jabatans = Jabatan::pluck('name', 'id');
        $resignReasons = ResignReason::pluck('name', 'id');
        return view('karyawan.edit', compact('karyawan','pelatihans', 'users', 'departemens', 'units', 'jabatans', 'resignReasons'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari formulir
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'nik' => 'nullable|numeric|digits:4|unique:karyawans,nik,'.$id,
            'user_id' => 'nullable',
            'departemen_id' => 'nullable',
            'jabatan_id' => 'nullable',
            'unit_id' => 'nullable',
            'nomer_ktp' => 'nullable',
            'tempat_lahir' => 'nullable',
            'tanggal_lahir' => 'nullable',
            'alamat_ktp' => 'nullable',
            'gender' => 'nullable',
            'institusi' => 'nullable',
            'pendidikan_terakhir' => 'nullable',
            'tahun_lulus' => 'nullable',
            'status_ktp' => 'nullable',
            'telepon' => 'nullable',
            'npwp' => 'nullable',
            'status_karyawan' => 'nullable', // kontrak_atau_tetap
            'tgl_kontrak1' => 'nullable', // tglmasukdinas
            'akhir_kontrak1' => 'nullable',
            'status' => 'nullable', // aktif atau resign
            'tgl_resign' => 'nullable',
            'resign_id' => 'nullable',
            'kontrak.*.id_kontrak' => 'nullable|exists:karyawan_kontrak,id_kontrak',
            'kontrak.*.tanggal_mulai' => 'required|date',
            'kontrak.*.tanggal_selesai' => 'required|date|after_or_equal:kontrak.*.tanggal_mulai',
            'kontrak.*.deskripsi_kontrak' => 'nullable|string|max:255',
            'new_pelatihan.*' => 'nullable|string|max:255|unique:pelatihans,name',
            'new_tanggal_expired.*' => 'nullable|date',
            'new_file.*' => 'nullable|file|mimes:pdf|max:2048', // 2 MB limit for PDF files
            'file.*' => 'nullable|file|mimes:pdf|max:2048', // 2 MB limit for other files as well
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Cari karyawan yang akan diperbarui
        $karyawan = Karyawan::findOrFail($id);

        // Perbarui data karyawan
        $karyawan->update([
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'nik' => $request->input('nik'),
            'status_karyawan' => $request->input('status_karyawan'),
            'tgl_kontrak1' => $request->input('tgl_kontrak1'),
            'akhir_kontrak1' => $request->input('akhir_kontrak1'),
            'tgl_kontrak2' => $request->input('tgl_kontrak2'),
            'akhir_kontrak2' => $request->input('akhir_kontrak2'),
            'status' => $request->input('status'),
            'tgl_resign' => $request->input('tgl_resign'),
            'resign_id' => $request->input('resign_id'),
            'nomer_ktp' => $request->input('nomer_ktp'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'alamat_ktp' => $request->input('alamat_ktp'),
            'gender' => $request->input('gender'),
            'status_ktp' => $request->input('status_ktp'),
            'telepon' => $request->input('telepon'),
            'npwp' => $request->input('npwp'),
            'departemen_id' => $request->input('departemen_id'),
            'jabatan_id' => $request->input('jabatan_id'),
            'unit_id' => $request->input('unit_id'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Perbarui data pendidikan karyawan
        $pendidikan = $karyawan->pendidikan;
        $pendidikan->update([
            'institusi' => $request->input('institusi'),
            'pendidikan_terakhir' => $request->input('pendidikan_terakhir'),
            'tahun_lulus' => $request->input('tahun_lulus'),
            'nomer_ijazah' => $request->input('nomer_ijazah'),
            'nomer_str' => $request->input('nomer_str'),
            'exp_str' => $request->input('exp_str'),
            'profesi' => $request->input('profesi'),
            'cert_profesi' => $request->input('cert_profesi'),
            'nomer_sip' => $request->input('nomer_sip'),
            'tgl_terbit_sip' => $request->input('tgl_terbit_sip'),
            'exp_sip' => $request->input('exp_sip'),
            //Tambahkan kolom lain di sini jika diperlukan
        ]);

        $pelatihanData = [];
        if ($request->has('pelatihan')) {
            foreach ($request->input('pelatihan') as $pelatihanId) {
                // Get the existing file path for this pelatihan
                $pelatihanRelation = $karyawan->pelatihans()->find($pelatihanId);
                $existingFile = $pelatihanRelation ? $pelatihanRelation->pivot->file : null;

                $pelatihanData[$pelatihanId] = [
                    'tanggal_expired' => $request->input("tanggal_expired.$pelatihanId"),
                    'file' => $existingFile, // Retain the existing file path if it exists
                ]; // Find the pelatihan relation

                // If a new file is uploaded, replace the old one
                if ($request->hasFile("file.$pelatihanId")) {
                    // Delete the old file if it exists
                    if ($existingFile && Storage::disk('public')->exists($existingFile)) {
                        Storage::disk('public')->delete($existingFile);
                    }

                    // Store the new file and update the file path
                    $file = $request->file("file.$pelatihanId");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('pelatihan_files', $filename, 'public');
                    $pelatihanData[$pelatihanId]['file'] = $filePath; // Update with the new file path
                }
            }
        }
        $karyawan->pelatihans()->sync($pelatihanData);

        // Handling new pelatihan entries
        if ($request->has('new_pelatihan')) {
            foreach ($request->input('new_pelatihan') as $index => $newPelatihanName) {
                $newPelatihan = Pelatihan::create([
                    'name' => $newPelatihanName,
                ]);

                $filePath = null;
                if ($request->hasFile("new_file.$index")) {
                    $file = $request->file("new_file.$index");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('pelatihan_files', $filename, 'public');
                }

                // Attach the new Pelatihan entry with optional file and expiration date
                $karyawan->pelatihans()->attach($newPelatihan->id, [
                    'tanggal_expired' => $request->input("new_tanggal_expired.$index"),
                    'file' => $filePath,
                ]);
            }
        }

        // Update kontrak karyawan
        // Ambil semua ID kontrak yang disediakan dalam input
        $kontrakIds = array_filter(array_column($request->input('kontrak', []), 'id_kontrak'));  // Ganti 'id_kontrak'

        // Hapus kontrak yang tidak ada di input
        $karyawan->kontrak()->whereNotIn('id_kontrak', $kontrakIds)->delete();  // Ganti 'id_kontrak'

        // Update atau buat kontrak baru hanya jika ada perubahan
        foreach ($request->input('kontrak', []) as $kontrakData) {
            if (isset($kontrakData['id_kontrak'])) {
                // Update kontrak yang sudah ada hanya jika ada perubahan
                $kontrak = $karyawan->kontrak()->find($kontrakData['id_kontrak']);
                if ($kontrak && (
                    $kontrak->tanggal_mulai != $kontrakData['tanggal_mulai'] ||
                    $kontrak->tanggal_selesai != $kontrakData['tanggal_selesai'] ||
                    $kontrak->deskripsi_kontrak != $kontrakData['deskripsi_kontrak']
                )) {
                    $kontrak->update([
                        'tanggal_mulai' => $kontrakData['tanggal_mulai'],
                        'tanggal_selesai' => $kontrakData['tanggal_selesai'],
                        'deskripsi_kontrak' => $kontrakData['deskripsi_kontrak'],
                    ]);
                }
            } else {
                // Tambahkan kontrak baru hanya jika belum ada
                $karyawan->kontrak()->create([
                    'tanggal_mulai' => $kontrakData['tanggal_mulai'],
                    'tanggal_selesai' => $kontrakData['tanggal_selesai'],
                    'deskripsi_kontrak' => $kontrakData['deskripsi_kontrak'],
                ]);
            }
        }
        // Tambahkan pesan sukses ke session flash
        $message = 'Data karyawan berhasil diperbarui';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('karyawan');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Karyawan::find($id);

        if ($data) {
            $data->delete();
        }
        // Tambahkan session flash message
        $message = 'Karyawan Berhasil Di Hapus';
        Session::flash('successAdd', $message);
        
        return redirect()->route('karyawan');
    }

    public function destroyKontrak($karyawanId, $kontrakId)
    {
        // Cari karyawan berdasarkan ID
        $karyawan = Karyawan::findOrFail($karyawanId);
    
        // Cari kontrak berdasarkan ID kontrak
        $kontrak = $karyawan->kontrak()->where('id_kontrak', $kontrakId)->first();
    
        if ($kontrak) {
            // Hapus kontrak
            $kontrak->delete();
            // Berikan pesan sukses
            Session::flash('successAdd', 'Kontrak berhasil dihapus.');
        } else {
            // Jika kontrak tidak ditemukan
            Session::flash('error', 'Kontrak tidak ditemukan.');
        }
    
        // Redirect kembali ke halaman edit karyawan
        return redirect()->route('karyawan.edit', $karyawanId);
    }
    
    
}
