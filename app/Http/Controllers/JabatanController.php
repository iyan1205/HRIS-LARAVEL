<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Providers\ApproverServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    protected $ApproverServiceProvider;
    public function __construct(ApproverServiceProvider $approverService)
    {
        $this->ApproverServiceProvider = $approverService;
    }

    public function getApprover($jabatanId)
    {
        $jabatan = Jabatan::findOrFail($jabatanId);
        $approver = $this->ApproverServiceProvider->getApproverForJabatan($jabatan);

        if ($approver) {
            return response()->json(['approver' => $approver]);
        } else {
            return response()->json(['message' => 'Tidak ada atasan langsung untuk jabatan ini'], 404);
        }
    }

    public function index()
    {
        $jabatans = Jabatan::get();
        return view('organisasi.jabatan.index', compact('jabatans'));
    }
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('organisasi.jabatan.create', compact('jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:jabatans',
            'manager_id' => 'required'
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
        $jabatans = Jabatan::all();
        $selectedManagerIds = $jabatan->subordinates->pluck('id')->toArray(); // Mengambil id atasan yang sudah dipilih sebelumnya
        return view('organisasi.jabatan.edit', compact('jabatan', 'jabatans', 'selectedManagerIds'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
    $jabatan->update([
        'name' => $request->name,
    ]);

    // Update subordinates
    $selectedManagerIds = $request->input('manager_id', []);
    $jabatan->subordinates()->update(['manager_id' => null]); // Hapus atasan sebelumnya
    foreach ($selectedManagerIds as $managerId) {
        $subordinate = Jabatan::findOrFail($managerId);
        $subordinate->update(['manager_id' => $jabatan->id]); // Set atasan baru
    }    // Tambahkan session flash message
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
