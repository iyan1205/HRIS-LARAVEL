<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Mobilitas;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MobilitasJabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super-Admin|admin');
    }

    function index(request $request) 
    {
        $perPage = in_array($request->per_page, [10, 25, 50]) ? $request->per_page : 10;
        $mobilitasJabatans = Mobilitas::with('karyawan')
            ->when($request->filled('nama'), function ($q) use ($request) {
                $q->whereHas('karyawan', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->nama . '%');
                });
            })
            ->when($request->filled('aspek'), function ($q) use ($request) {
                $q->where('aspek', 'like', '%' . $request->aspek . '%');
            })
            ->when($request->filled('jabatan_sebelumnya'), function ($q) use ($request) {
                $q->where('jabatan_sekarang', 'like', '%' . $request->jabatan_sebelumnya . '%');
            })
            ->when($request->filled('jabatan_baru'), function ($q) use ($request) {
                $q->where('jabatan_baru', 'like', '%' . $request->jabatan_baru . '%');
            })
            ->when($request->filled('departemen_sebelumnya'), function ($q) use ($request) {
                $q->where('departemen_sekarang', 'like', '%' . $request->departemen_sebelumnya . '%');
            })
            ->when($request->filled('departemen_baru'), function ($q) use ($request) {
                $q->where('departemen_baru', 'like', '%' . $request->departemen_baru . '%');
            })
            ->when($request->filled('instalasi_divisi_sebelumnya'), function ($q) use ($request) {
                $q->where('unit_sekarang', 'like', '%' . $request->instalasi_divisi_sebelumnya . '%');
            })
            ->when($request->filled('instalasi_divisi_baru'), function ($q) use ($request) {
                $q->where('unit_baru', 'like', '%' . $request->instalasi_divisi_baru . '%');
            })
            ->orderByRaw('STR_TO_DATE(tanggal_efektif, "%Y-%m-%d") ASC')
            ->paginate($perPage)
            ->withQueryString();
        return view('organisasi.jabatan.mobilitas-jabatan', compact('mobilitasJabatans'));
    }

    function edit(Request $request, $id) {
        $mobilitasData = Mobilitas::find($id);
        $departemens = Departemen::pluck('name', 'name');
        $units = Unit::pluck('name', 'name');
        $jabatans = Jabatan::pluck('name', 'name');
        return view('organisasi.jabatan.mobilitas-edit', compact('mobilitasData','departemens','units','jabatans'));
    }

    function update(Request $request, string $id){
         $validator = Validator::make($request->all(), [
            'aspek' => 'nullable',
            'jabatan_baru' => 'nullable',
            'departemen_baru' => 'nullable',
            'unit_baru' => 'nullable',
            'tanggal_efektif' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $mobilitasData = Mobilitas::findOrFail($id);
        $mobilitasData->update([
            'aspek' => $request->input('aspek'),
            'jabatan_baru' => $request->input('jabatan_baru'),
            'departemen_baru' => $request->input('departemen_baru'),
            'unit_baru' => $request->input('unit_baru'),
            'tanggal_efektif' => $request->input('tanggal_efektif')
        ]);
        Session::flash('successAdd', 'Data mobilitas jabatan berhasil diperbarui');
        return redirect()->route('mobilitas.index');
    }

}
