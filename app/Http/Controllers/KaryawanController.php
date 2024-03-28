<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
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
        return view('karyawan.create', compact('departemens', 'units', 'jabatans', 'users'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nik' => 'required',
            'user_id' => 'nullable',
            'departemen_id' => 'required',
            'jabatan_id' => 'required',
            'unit_id' => 'required',
            'nomer_ktp' => 'nullable',
            'tempat_lahir' => 'nullable',
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
            'status' => 'required', //active atau resign
            'tgl_resign' => 'required',
            'resign_id' => 'required',

            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            // Mengecek tab yang aktif dan mengarahkan kembali sesuai dengan tab yang aktif
            if ($request->input('active_tab') == 'karyawan') {
                return redirect()->back()->withInput()->withErrors($validator)->with('active_tab', 'karyawan');
            } elseif ($request->input('active_tab') == 'pendidikan') {
                return redirect()->back()->withInput()->withErrors($validator)->with('active_tab', 'pendidikan');
            }
        }

        // Buat dan simpan karyawan baru
        $karyawan = Karyawan::create([
            'name' => $request->input('name'),
            'nik' => $request->input('nik'),
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
            //Tambahkan kolom lain di sini jika diperlukan
        ]);

        $karyawan->pendidikan()->save($pendidikan);

        // Tambahkan session flash message
        $message = 'Karyawan berhasil ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('karyawan', $karyawan->id);
    }


    public function storePendidikan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'institusi' => 'nullable',
            'pendidikan_terakhir' => 'nullable',
            'tahun_lulus' => 'nullable',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $pendidikan = Pendidikan::create([
            'user_id' => $request->input('user_id'),
            'institusi' => $request->input('institusi'),
            'pendidikan_terakhir' => $request->input('pendidikan_terakhir'),
            'tahun_lulus' => $request->input('tahun_lulus'),
        ]);
        // Tambahkan session flash message
        $message = 'Pendidikan Karyawan berhasil ditambahkan';
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
        $users = User::pluck('name', 'id');
        $departemens = Departemen::pluck('name', 'id');
        $units = Unit::pluck('name', 'id');
        $jabatans = Jabatan::pluck('name', 'id');
        $resignReasons = ResignReason::pluck('name', 'id');
        return view('karyawan.edit', compact('karyawan', 'users', 'departemens', 'units', 'jabatans', 'resignReasons'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form karyawan
        $validatorKaryawan = Validator::make($request->all(), [
            'user_id' => 'nullable',
            'departemen_id' => 'nullable|exists:departemens,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'unit_id' => 'nullable|exists:units,id',
            'name' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:4',
            'status_karyawan' => 'nullable|string|max:255',
            'tgl_kontrak1' => 'nullable|string|max:255',
            'akhir_kontrak1' => 'nullable|string|max:255',
            'tgl_kontrak2' => 'nullable|string|max:255',
            'akhir_kontrak2' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'resign' => 'nullable|string',
            'resign_id' => 'nullable|exists:resign_reasons,id',
            'nomer_ktp' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|date',
            'alamat_ktp' => 'nullable|string',
            'status_ktp' => 'nullable|string',
            'telepon' => 'nullable|string',
            'npwp' => 'nullable|string',

        ]);

        if ($validatorKaryawan->fails()) {
            return redirect()->back()->withErrors($validatorKaryawan)->withInput();
        }

        // Update data karyawan
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($validatorKaryawan->validated());

        // Jika ada informasi pendidikan yang diubah, lakukan pembaruan juga
        if ($karyawan->pendidikan) {
            $karyawan->pendidikan->update([
                'institusi' => $request->institusi,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tahun_lulus' => $request->tahun_lulus
                // Tambahkan pembaruan untuk field pendidikan lainnya jika diperlukan
            ]);
        } else {
            // Jika informasi pendidikan tidak ada sebelumnya, tambahkan data baru
            $karyawan->pendidikan()->create([
                'institusi' => $request->institusi,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tahun_lulus' => $request->tahun_lulus
                // Tambahkan field pendidikan lainnya jika diperlukan
            ]);
        }
        // Tambahkan session flash message
        $message = 'Data Karyawan Berhasil Diedit';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->back();
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
