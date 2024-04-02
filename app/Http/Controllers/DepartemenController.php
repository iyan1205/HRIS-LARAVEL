<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DepartemenController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super-Admin|admin');
    }
    
    public function index()
    {
        $departemens = Departemen::get();
        return view('organisasi.departemen.index', compact('departemens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organisasi.departemen.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departemens',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan departemen baru
        $departemens = Departemen::create([
            'name' => $request->input('name'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Departemen berhasil ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('departemen');
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
        $departemens = Departemen::find($id);
        return view('organisasi.departemen.edit', compact('departemens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|unique:departemens,name,'.$id,
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Temukan departemen yang ingin diperbarui
        $departmens = Departemen::findOrFail($id);

        // Perbarui atribut departemen sesuai dengan data yang diterima dari request
        $departmens->update([
        'name' => $request->input('name'),

        ]);

        // Tambahkan session flash message
        $message = 'Department Berhasil Diedit';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('departemen');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Departemen::find($id);

        if ($data) {
            $data->delete();
        }

        $message = 'Department Berhasil Dihapus';
        Session::flash('successAdd', $message);
        return redirect()->route('departemen');
    }
}
