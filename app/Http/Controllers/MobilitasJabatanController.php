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

    function index() {
        $mobilitasData = Mobilitas::orderBy('tanggal_efektif', 'desc')->get();
        return view('organisasi.jabatan.mobilitas-jabatan', compact('mobilitasData'));
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
