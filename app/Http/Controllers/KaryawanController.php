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
        $karyawan = Karyawan::with('pendidikan')->find($id);
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
        'tanggal_lahir' => 'nullable|date',
        'alamat_ktp' => 'nullable',
        'gender' => 'nullable',
        'institusi' => 'nullable',
        'pendidikan_terakhir' => 'nullable',
        'tahun_lulus' => 'nullable|numeric',
        'status_ktp' => 'nullable',
        'telepon' => 'nullable',
        'npwp' => 'nullable',
        'status_karyawan' => 'nullable',
        'tgl_kontrak1' => 'nullable|date',
        'akhir_kontrak1' => 'nullable|date|after_or_equal:tgl_kontrak1',
        'status' => 'nullable',
        'tgl_resign' => 'nullable|date',
        'resign_id' => 'nullable',
        
        'new_pelatihan.*' => 'nullable|string|max:255|unique:pelatihans,name',
        'new_tanggal_expired.*' => 'nullable|date',
        'new_file.*' => 'nullable|file|mimes:pdf|max:2048',
        'file.*' => 'nullable|file|mimes:pdf|max:2048',

        'kontrak.*.id' => 'nullable|exists:kontrak_karyawan,id',
        'kontrak.*.tanggal_mulai' => 'required|date',
        'kontrak.*.tanggal_selesai' => [
            'required',
            'date',
            function ($attribute, $value, $fail) use ($request) {
                $index = explode('.', $attribute)[1];
                $tanggalMulai = $request->input("kontrak.$index.tanggal_mulai");
                if ($tanggalMulai && $value < $tanggalMulai) {
                    $fail("Tanggal selesai harus setelah atau sama dengan tanggal mulai.");
                }
            },
        ],
        'kontrak.*.deskripsi_kontrak' => [
            'nullable',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($request) {
                $descriptions = array_column($request->input('kontrak', []), 'deskripsi_kontrak');
                if (count(array_filter($descriptions)) !== count(array_unique(array_filter($descriptions)))) {
                    $fail("Deskripsi kontrak tidak boleh sama.");
                }
            },
        ],
    ]);

    // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }

    // Cari karyawan yang akan diperbarui
    $karyawan = Karyawan::findOrFail($id);

    // Perbarui data karyawan
    $karyawan->update($request->only([
        'user_id', 'name', 'nik', 'status_karyawan', 'status', 'tgl_resign', 'resign_id',
        'nomer_ktp', 'tempat_lahir', 'tanggal_lahir', 'alamat_ktp', 'gender',
        'status_ktp', 'telepon', 'npwp', 'departemen_id', 'jabatan_id', 'unit_id'
    ]));

    // Perbarui data pendidikan karyawan jika ada
    if ($karyawan->pendidikan) {
        $karyawan->pendidikan->update($request->only([
            'institusi', 'pendidikan_terakhir', 'tahun_lulus', 'nomer_ijazah',
            'nomer_str', 'exp_str', 'profesi', 'cert_profesi','nomer_sip', 'tgl_terbit_sip', 'exp_sip'
        ]));
    }

    // Update pelatihan
    $pelatihanData = [];
    if ($request->has('pelatihan')) {
        foreach ($request->input('pelatihan') as $pelatihanId) {
            $pelatihanRelation = $karyawan->pelatihans()->find($pelatihanId);
            $existingFile = $pelatihanRelation ? $pelatihanRelation->pivot->file : null;

            $pelatihanData[$pelatihanId] = [
                'tanggal_expired' => $request->input("tanggal_expired.$pelatihanId"),
                'file' => $existingFile,
            ];

            if ($request->hasFile("file.$pelatihanId")) {
                if ($existingFile && Storage::disk('public')->exists($existingFile)) {
                    Storage::disk('public')->delete($existingFile);
                }
                $file = $request->file("file.$pelatihanId");
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('pelatihan_files', $filename, 'public');
                $pelatihanData[$pelatihanId]['file'] = $filePath;
            }
        }
    }
    $karyawan->pelatihans()->sync($pelatihanData);

    // Menambahkan pelatihan baru jika ada
    if ($request->has('new_pelatihan')) {
        foreach ($request->input('new_pelatihan') as $index => $newPelatihanName) {
            $newPelatihan = Pelatihan::create(['name' => $newPelatihanName]);

            $filePath = null;
            if ($request->hasFile("new_file.$index")) {
                $file = $request->file("new_file.$index");
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('pelatihan_files', $filename, 'public');
            }

            $karyawan->pelatihans()->attach($newPelatihan->id, [
                'tanggal_expired' => $request->input("new_tanggal_expired.$index"),
                'file' => $filePath,
            ]);
        }
    }

    // Update kontrak
    $kontrakIds = array_filter(array_column($request->input('kontrak', []), 'id'));
    $karyawan->kontrak()->whereNotIn('id', $kontrakIds)->delete();

    foreach ($request->input('kontrak', []) as $kontrakData) {
        if (!empty($kontrakData['deskripsi_kontrak'])) {
            if (!empty($kontrakData['id'])) {
                $kontrak = $karyawan->kontrak()->find($kontrakData['id']);
                if ($kontrak) {
                    $kontrak->update($kontrakData);
                }
            } else {
                $karyawan->kontrak()->create($kontrakData);
            }
        }
    }

    if ($request->has('kontrak_hapus')) {
        $karyawan->kontrak()->whereIn('id', $request->kontrak_hapus)->delete();
    }

    Session::flash('successAdd', 'Data karyawan berhasil diperbarui');
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

    public function mobilitas(Request $request, $id)
    {
        $karyawan = Karyawan::find($id);
        $users = User::pluck('name', 'id');
        $departemens = Departemen::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');
        $jabatans = Jabatan::pluck('name', 'id');
        return view('karyawan.mobilitas', compact('karyawan','users', 'departemens', 'units', 'jabatans'));
        
    }

    public function mobilitasUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jabatan_sekarang' => 'required|exists:jabatans,id',
            'jabatan_baru' => 'required|exists:jabatans,id',
            'departemen_sekarang' => 'required|exists:departemens,id',
            'departemen_baru' => 'required|exists:departemens,id',
            'unit_sekarang' => 'required|exists:units,id',
            'unit_baru' => 'required|exists:units,id',

            'karyawan_id' => 'required',
            'aspek' => 'required',
            'tanggal_efektif' => 'required|date',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $karyawan = Karyawan::findOrFail($id);
        // Simpan ID baru di tabel karyawan
        $karyawan->update([
            'departemen_id' => $request->input('departemen_baru'),
            'jabatan_id' => $request->input('jabatan_baru'),
            'unit_id' => $request->input('unit_baru')
        ]);
    
        $mobilitasData = [
            'karyawan_id' => $id,
            'aspek' => $request->input('aspek'),
            'jabatan_sekarang' =>  Jabatan::find($request->input('jabatan_sekarang'))->name,
            'jabatan_baru' => Jabatan::find($request->input('jabatan_baru'))->name,
            'departemen_sekarang' => Departemen::find($request->input('departemen_sekarang'))->name,
            'departemen_baru' => Departemen::find($request->input('departemen_baru'))->name,
            'unit_sekarang' => Unit::find($request->input('unit_sekarang'))->name,
            'unit_baru' => Unit::find($request->input('unit_baru'))->name,
            'tanggal_efektif' => $request->input('tanggal_efektif')
        ];
    
        $karyawan->mobilitas()->create($mobilitasData);
    
        Session::flash('successAdd', 'Data mobilitas jabatan berhasil dibuat');
        return redirect()->route('karyawan');
    }

}
