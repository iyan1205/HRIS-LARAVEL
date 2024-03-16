<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::get();
        return view('organisasi.jabatan.index', compact('jabatans'));
    }
    public function create()
    {
        return view('organisasi.jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:jabatans',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan jabatan baru
        $jabatans = Jabatan::create([
            'name' => $request->input('name'),
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
    public function edit(Request $request, $id)
    {
        $jabatans = Jabatan::find($id);
        return view('organisasi.jabatan.edit', compact('jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Temukan jabatan yang ingin diperbarui
        $jabatans = Jabatan::findOrFail($id);

        // Perbarui atribut jabatan sesuai dengan data yang diterima dari request
        $jabatans->name = $request->input('name');
        // Tambahkan kolom lain yang perlu diperbarui

        // Simpan perubahan ke dalam database
        $jabatans->save();

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
