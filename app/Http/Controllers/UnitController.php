<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super-Admin|admin');
    }
    
    public function index()
    {
        $units = Unit::get();
        return view('organisasi.unit.index', compact('units'));
    }
    public function create()
    {
        return view('organisasi.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan unit baru
        $units = Unit::create([
            'name' => $request->input('name'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Unit Berhasil Ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('unit');
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
        $units = Unit::find($id);
        return view('organisasi.unit.edit', compact('units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:units,name,'. $id,
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Temukan unit yang ingin diperbarui
        $units = Unit::findOrFail($id);

        // Perbarui atribut unit sesuai dengan data yang diterima dari request
        $units->name = $request->input('name');
        // Tambahkan kolom lain yang perlu diperbarui

        // Simpan perubahan ke dalam database
        $units->save();

        // Tambahkan session flash message
        $message = 'Unit Berhasil Diedit';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('unit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Unit::find($id);

        if ($data) {
            $data->delete();
        }
        // Tambahkan session flash message
        $message = 'Unit Berhasil Di Hapus';
        Session::flash('successAdd', $message);
        
        return redirect()->route('unit');
    }
}
