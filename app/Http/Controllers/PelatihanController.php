<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;

class PelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelatihans = Pelatihan::all();
        return view('pelatihan.index', compact('pelatihans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('pelatihan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:128|unique:pelatihans',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan pelatihan baru
        $pelatihans = Pelatihan::create([
            'name' => $request->input('name'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Pelatihan berhasil ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('pelatihan');
    }

public function viewCertificate($file)
{
    $path = storage_path('app/public/pelatihan_files/' . $file); // Adjusted path
    if (file_exists($path)) {
        // Create a new PDF instance
        $pdf = new FPDI();
        $pdf->AddPage();
        
        // Set the source file (the existing PDF)
        $pageCount = $pdf->setSourceFile($path);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->useTemplate($templateId);
            
            // Add watermark to each page
            $pdf->SetFont('Arial', 'B', 50);
            $pdf->SetTextColor(200, 200, 200); // Light gray color
            $pdf->SetXY(10, 100); // Position the watermark
            $pdf->Text(50, 100, 'RS HAMORI'); // Add the watermark text
        }

        // Output the PDF as inline
        $pdf->Output('I', 'certificate_with_watermark.pdf'); // 'I' for inline display
        exit;
    }
    return abort(404); // If file does not exist
}

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelatihans = Pelatihan::find($id);
        return view('pelatihan.edit', compact('pelatihans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validasi input dari form
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:128|unique:pelatihans,name,'.$id ,
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
         // Temukan departemen yang ingin diperbarui
         $departmens = Pelatihan::findOrFail($id);

         // Perbarui atribut departemen sesuai dengan data yang diterima dari request
         $departmens->update([
         'name' => $request->input('name'),
 
         ]);
 
         // Tambahkan session flash message
         $message = 'Pelatihan Berhasil Diedit';
         Session::flash('successAdd', $message);
 
         // Redirect ke halaman tertentu atau tampilkan pesan sukses
         return redirect()->route('pelatihan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
