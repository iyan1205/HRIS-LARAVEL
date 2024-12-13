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
            'tgl_kontrak1' => 'required', //tglmasukdinas
            'akhir_kontrak1' => 'required',

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
            'tgl_kontrak1' => $request->input('tgl_kontrak1'),
            'akhir_kontrak1' => $request->input('akhir_kontrak1'),
            // Tambahkan kolom lain yang perlu disimpan
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
            //Tambahkan kolom lain di sini jika diperlukan
        ]);

        $karyawan->pendidikan()->save($pendidikan);

        // Menambahkan pelatihan karyawan
        if ($request->has('pelatihan')) {
            $karyawan->pelatihans()->attach($request->pelatihan);
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

        if ($request->has('pelatihan')) {
            $karyawan->pelatihans()->sync($request->pelatihan);
        } else {
            // Jika tidak ada pelatihan yang dipilih, bersihkan relasi pelatihan karyawan
            $karyawan->pelatihans()->detach();
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
}
