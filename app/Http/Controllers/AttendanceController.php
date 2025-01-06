<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Jenssegers\Agent\Agent;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah check-in dan belum check-out
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->latest()
                                ->first();

        return view('attendance.index', compact('attendance'));
    }

    public function checkIn(Request $request)
    {
        
        if($request->file('foto_jam_masuk')){
            $manager = new ImageManager(new Driver());
            $name_img = hexdec(uniqid()).'.'.$request->file('foto_jam_masuk')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_masuk'));
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/'.$name_img));
            $path = 'attendance/'.$name_img;
        }
        $ipAddress = $request->ip();

        // Parsing informasi perangkat menggunakan library Jenssegers Agent
        $agent = new Agent();
        $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
        $platform = $agent->platform(); // Contoh: Windows, iOS, Android
        $browser = $agent->browser();   // Contoh: Chrome, Safari

        $deviceInfo = "{$deviceType} | {$platform} | {$browser}";
        Attendance::create([
            'user_id' => Auth::id(),
            'jam_masuk' => now(),
            'ip_address' => $ipAddress,
            'device_info' => $deviceInfo,
            'foto_jam_masuk' => $path,
            'status' => 'hadir',
        ]);

        return redirect()->route('attendance.index')->with('success', 'Berhasil Check-in');
    }

    public function checkOut(Request $request)
    {
        $path = null; // Initialize path variable

        if ($request->file('foto_jam_keluar')) {
            $manager = new ImageManager(new Driver()); // No need to pass a driver explicitly
            $name_img = hexdec(uniqid()) . '.' . $request->file('foto_jam_keluar')->getClientOriginalExtension();
            $img = $manager->read($request->file('foto_jam_keluar')); // Correct method for reading and processing image
            $img->resize(200, 200);
            $img->save(storage_path('app/public/attendance/' . $name_img)); // Save to correct storage path
            $path = 'attendance/' . $name_img;
        }
        $ipAddress = $request->ip();

        // Parsing informasi perangkat menggunakan library Jenssegers Agent
        $agent = new Agent();
        $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
        $platform = $agent->platform(); // Contoh: Windows, iOS, Android
        $browser = $agent->browser();   // Contoh: Chrome, Safari

        $deviceInfo = "{$deviceType} | {$platform} | {$browser}";

        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereNull('jam_keluar')
                                ->first();

        if ($attendance) {
            $attendance->update([
                'jam_keluar' => now(),
                'foto_jam_keluar' => $path,
                'ip_address' => $ipAddress,
                'device_info' => $deviceInfo,
                'status' => 'pulang',
            ]);

            return redirect()->route('attendance.index')->with('success', 'Check-out berhasil.');
        }

        return redirect()->route('attendance.index')->with('error', 'Data absensi tidak ditemukan.');
    }

    public function list_attendance() {
        $attendance = Attendance::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('attendance.list', compact('attendance'));
    }

    public function find_attendance(Request $request){
        $request->validate([
            'date' => 'required|date',
        ]);
    
        // Ambil data berdasarkan tanggal
        $date = $request->input('date');
        $attendance = Attendance::where('user_id', Auth::id())
                                ->whereDate('created_at', $date)
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Tampilkan view dengan hasil pencarian
        return view('attendance.list', compact('attendance'));
    }

    public function laporan() {
        return view('attendance.list-laporan');
    }

    public function find_attendance_report(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data berdasarkan rentang tanggal
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $attendance = Attendance::with('user.karyawan.jabatan') // Eager load relasi
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();
    
        // Tampilkan view dengan hasil pencarian
        return view('attendance.list-laporan', compact('attendance', 'startDate', 'endDate'));
    }

    public function download_attendance_report(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

            ReportHistory::create([
                'user_id' => Auth::id(), // Jika user login
                'start_date' => $startDate,
                'end_date' => $endDate,
                'ip_address' => $request->ip(),
                'name' => 'Absensi'    
            ]);
            // Ambil data absensi
        $attendance = Attendance::with('user.karyawan.jabatan')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan header ke file Excel
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Nik');
        $sheet->setCellValue('D1', 'Jabatan');
        $sheet->setCellValue('E1', 'Departemen');
        $sheet->setCellValue('F1', 'Unit');
        $sheet->setCellValue('G1', 'Tanggal dan Jam Masuk');
        $sheet->setCellValue('H1', 'Tanggal dan Jam Keluar');
        $sheet->setCellValue('I1', 'Total Jam');
        $sheet->setCellValue('J1', 'Status Kehadiran');
        // Set header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],  // Background blue color for header
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        
        // Set header style
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Tambahkan data ke file Excel
        $row = 2;
        foreach ($attendance as $record) {
            $jamMasuk = Carbon::parse($record->created_at);
            $jamKeluar = Carbon::parse($record->updated_at);
            // Hitung selisih jam
            if ($jamMasuk->eq($jamKeluar)) {
                // Jika jamKeluar sama dengan jamMasuk (belum absen pulang)
                $totalJam = 'Belum absen pulang';
                $statusKehadiran = 'Belum absen pulang';
            } else {
                // Hitung durasi jika ada jam keluar
                $totalJam = $jamMasuk->diffInHours($jamKeluar) . ' Jam ' . $jamMasuk->diffInMinutes($jamKeluar) % 60 . ' Menit';
                
                // Tentukan status berdasarkan selisih waktu
                if ($jamMasuk->diffInHours($jamKeluar) > 12) {
                    // Jika jamKeluar lebih dari 12 jam setelah jamMasuk
                    $totalJam = 'Tidak absen pulang';
                    $statusKehadiran = 'Tidak Hadir';
                } else {
                    $statusKehadiran = 'Hadir';
                }
            }
            $sheet->setCellValue('A' . $row, $record->id);
            $sheet->setCellValue('B' . $row, $record->user->karyawan->name);
            $sheet->setCellValue('C' . $row, $record->user->karyawan->nik);
            $sheet->setCellValue('D' . $row, $record->user->karyawan->jabatan->name);
            $sheet->setCellValue('E' . $row, $record->user->karyawan->departemen->name);
            $sheet->setCellValue('F' . $row, $record->user->karyawan->unit->name);
            $sheet->setCellValue('G' . $row, $jamMasuk->format('d/m/Y H:i:s'));
            // Cek apakah ada waktu keluar
            if ($jamMasuk->eq($jamKeluar)) {
                $sheet->setCellValue('H' . $row, '-');
            } else {
                $sheet->setCellValue('H' . $row, $jamKeluar->format('d/m/Y H:i:s'));
            }

           // Menambahkan total jam atau status
            if ($totalJam === 'Belum absen pulang' || $totalJam === 'Tidak absen pulang') {
                $sheet->setCellValue('I' . $row, $totalJam);
                $sheet->getStyle('I' . $row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
            } else {
                $sheet->setCellValue('I' . $row, $totalJam);
            }

            // Status Kehadiran
            $sheet->setCellValue('J' . $row, $statusKehadiran);

            // Apply border style for each cell
            $sheet->getStyle('A' . $row . ':J' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $row++;
        }
        // Apply borders to header
        $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Autofit column widths
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        // Buat writer untuk file Excel
        $writer = new Xlsx($spreadsheet);

        // Tentukan nama file
        $fileName = "absensi_kehadiran_{$startDate}_to_{$endDate}.xlsx";

        // Kirim file ke browser untuk diunduh
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function report_history_absensi(){
        $reporthistory = ReportHistory::with('user')->where('name','Absensi')->get();
        return view('attendance.report-history', compact('reporthistory'));
    }    
}