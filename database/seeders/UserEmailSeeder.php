<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Pendidikan;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lokasi file Excel
        $excelFile = storage_path('app/public/useremail.xlsx');

      // Membaca file Excel
      $spreadsheet = IOFactory::load($excelFile);
      $sheet = $spreadsheet->getActiveSheet();
      $rows = $sheet->toArray();

      // Iterasi setiap baris dari file Excel
      foreach ($rows as $row) {
          // Menghapus tanda kutip pada kolom "nik" jika ada
          $nik = $row[5]; // Kolom "nik"
          if (substr($nik, 0, 1) == "'") {
              $nik = substr($nik, 1);
          }

          // Menghapus tanda kutip pada kolom "nomor_telepon" jika ada
          $telepon = $row[15]; // Kolom "telepon"
          if (substr($telepon, 0, 1) == "'") {
              $telepon = substr($telepon, 1);
          }

          // Membuat sebuah pengguna baru
          $user = new User();
          $user->id = Str::uuid(); // Menggunakan UUID sebagai ID pengguna
          $user->name = $row[0]; // Kolom name
          $user->email = $row[1]; // Kolom email
          $user->password = Hash::make('12345678'); // Meng-hash password default
          $user->save();

          $user->assignRole('karyawan');

          // Menambahkan informasi karyawan (termasuk "nik")
        $karyawan = new Karyawan();
        $karyawan->user_id = $user->id; // Set ID pengguna baru
        $karyawan->name = $row[3]; // Kolom alamat (misalnya)
        $karyawan->nik = $nik;
        $karyawan->gender = $row[4];
        $karyawan->status_karyawan = $row[5];
        $karyawan->tgl_kontrak1 = $row[6];
        $karyawan->akhir_kontrak1 = $row[7];
        $karyawan->tgl_kontrak2 = $row[8];
        $karyawan->akhir_kontrak2 = $row[9];
        $karyawan->nomer_ktp = $row[10];
        $karyawan->tempat_lahir = $row[11];
        $karyawan->tanggal_lahir = $row[12];
        $karyawan->alamat_ktp = $row[13];
        $karyawan->status_ktp = $row[14];
        $karyawan->telepon = $telepon; // Kolom nomor telepon (misalnya)
        $karyawan->npwp = $row[16];
        $karyawan->departemen_id = $row[17];
        $karyawan->jabatan_id= $row[18];
        $karyawan->unit_id= $row[19];
        $karyawan->status = $row[30];
        $karyawan->tgl_resign = $row[28];
        $karyawan->resign_id = $row[29];
        $karyawan->save();
        
        $pendidikan = new Pendidikan();
        $pendidikan->karyawan_id = $karyawan->id;
        $pendidikan->institusi = $row[21];
        $pendidikan->tahun_lulus = $row[23];
        $pendidikan->pendidikan_terakhir = $row[22];
        $pendidikan->nomer_ijazah = $row[20];
        $pendidikan->nomer_str = $row[24];
        $pendidikan->exp_str = $row[25];
        $pendidikan->profesi = $row[26];
        $pendidikan->cert_profesi = $row[27];
        $pendidikan->save();
        
      }
  }
}