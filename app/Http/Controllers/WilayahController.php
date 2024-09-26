<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        // Konsumsi API untuk mendapatkan data provinsi
        $response = Http::get('https://dev.farizdotid.com/api/daerahindonesia/provinsi');

        if ($response->successful()) {
            $dataProvinsi = $response->json();
            return view('provinsi', ['provinsi' => $dataProvinsi['provinsi']]);
        }

        return response()->json(['message' => 'Gagal mendapatkan data provinsi'], 500);
    }

    // Fungsi untuk menampilkan kota berdasarkan ID provinsi
    public function getKota(Request $request, $id_provinsi)
    {
        // Konsumsi API untuk mendapatkan data kota berdasarkan ID provinsi
        $response = Http::get("https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=$id_provinsi");

        if ($response->successful()) {
            $dataKota = $response->json();
            return view('kota', ['kota' => $dataKota['kota']]);
        }

        return response()->json(['message' => 'Gagal mendapatkan data kota'], 500);
    }

    public function getKotaByProvinsi($id_provinsi)
{
    $response = Http::get("https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=$id_provinsi");

    // Periksa apakah respons API berhasil
    if ($response->successful()) {
        $data = $response->json();

        // Periksa apakah kunci 'kota_kabupaten' ada dalam respons
        if (isset($data['kota_kabupaten'])) {
            return response()->json($data['kota_kabupaten']);
        } else {
            return response()->json(['message' => 'Data kota tidak ditemukan'], 404);
        }
    }

    return response()->json(['message' => 'Gagal mendapatkan data kota'], 500);
}

}
