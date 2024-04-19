<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('role:Super-Admin|admin');
    }

    public function index()
    {
        $jabatans = Jabatan::orderBy('name', 'asc')->get();
        return view('organisasi.jabatan.index', compact('jabatans'));
    }
    public function create()
    {
        $jabatan = Jabatan::all();
        $jabatans = Jabatan::whereIn('level', ['Manajer', 'Kanit', 'SPV','Direktur'])->get();
        return view('organisasi.jabatan.create', compact('jabatan','jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:jabatans',
            'manager_id' => 'required',
            'level' => 'required'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan jabatan baru
        $jabatans = Jabatan::create([
            'name' => $request->input('name'),
            'manager_id' => $request->input('manager_id'),
            'level' => $request->input('level'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Jabatan Berhasil Ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('jabatan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatans = Jabatan::orderBy('name')->get();
        $selectedManagerIds = $jabatan->subordinates->pluck('id')->toArray(); // Mengambil id atasan yang sudah dipilih sebelumnya
        return view('organisasi.jabatan.edit', compact('jabatan', 'jabatans', 'selectedManagerIds'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|unique:jabatans,name,'.$id,
            'level' => 'nullable'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        // Update subordinates
        $selectedManagerIds = $request->input('manager_id', []);
        $jabatan->subordinates()->update(['manager_id' => null]); // Hapus atasan sebelumnya
        foreach ($selectedManagerIds as $managerId) {
        $subordinate = Jabatan::findOrFail($managerId);
        $subordinate->update(['manager_id' => $jabatan->id]); // Set atasan baru
        }    
        // Tambahkan session flash message
        $message = 'Jabatan Berhasil Di Edit';
        Session::flash('successAdd', $message);

            // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('jabatan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Jabatan::find($id);

        if ($data) {
            $data->delete();
        }
        // Tambahkan session flash message
        $message = 'Jabatan Berhasil Di Hapus';
        Session::flash('successAdd', $message);
        
        return redirect()->route('jabatan');
    }
}
