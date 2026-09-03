<?php

namespace App\Services;

use App\Models\BenefitKartap;
use App\Models\User;
use Carbon\Carbon;

class BenefitKartapService
{
    /** Bulan cooldown kacamata (1x per 2 tahun) */
    private const KACAMATA_COOLDOWN_BULAN = 24;

    /** Bulan siklus plafon MCU/Vitamin (reset setiap 1 tahun) */
    private const MCU_VITAMIN_SIKLUS_BULAN = 12;

    /** Bulan tunggu setelah pengangkatan sebelum bisa klaim pertama kali */
    private const ELIGIBILITY_BULAN = 3;

    /**
     * Status klaim yang dianggap "terpakai" terhadap plafon MCU/Vitamin.
     * Begitu diajukan (pending), plafon langsung berkurang — tidak menunggu approved.
     * Hanya 'rejected' yang tidak dihitung (dianggap batal, plafon dikembalikan).
     */
    private const STATUS_DIHITUNG = ['pending', 'approval_1', 'approval_2', 'approved','rejected'];

    /**
     * Status klaim Kacamata yang dianggap "menahan" cooldown 24 bulan.
     * BEDA dengan MCU/Vitamin: klaim rejected TETAP dihitung, karena klaim yang
     * ditolak masih bisa di-edit & diajukan ulang (bukan membuat klaim baru) —
     * jadi cooldown tetap berjalan sejak tanggal pengajuan awal.
     */
    private const STATUS_DIHITUNG_KACAMATA = ['pending', 'approval_1', 'approval_2', 'approved', 'rejected'];

    /**
     * Jenis benefit yang valid. Key HARUS PERSIS SAMA dengan:
     * - nilai kolom `jenis_benefit` di tabel benefit_kartaps
     * - key di config/benefit_limit.php (mis. benefit_limit.Staff.Kacamata)
     * Value dipakai sebagai label tampilan.
     */
    private const JENIS_LIST = [
        'Kacamata' => 'kacamata',
        'Vitamin'  => 'vitamin',
        'MCU'      => 'mcu',
    ];

    /**
     * Tanggal user pertama kali eligible untuk klaim apa pun
     * (awal bulan, 3 bulan setelah tanggal pengangkatan kartap).
     *
     * PENTING: tanggal pengangkatan diambil dari relasi karyawan
     * ($user->karyawan->tgl_kartap), BUKAN kolom langsung di tabel users.
     */
    public function tanggalEligibilitasPertama(User $user): Carbon
    {
        $tglKartap = $user->karyawan?->tgl_kartap;

        if (!$tglKartap) {
            throw new \RuntimeException(
                "Data Tanggal Kartap tidak ditemukan untuk Karyawan {$user->karyawan->name}. " .
                "Hubungi Admin SDM."
            );
        }

        return Carbon::parse($tglKartap)
            ->startOfMonth()
            ->addMonths(self::ELIGIBILITY_BULAN);
    }

    /**
     * Apakah user sudah lewat masa tunggu 3 bulan sejak pengangkatan.
     */
    public function sudahEligible(User $user, ?Carbon $now = null): bool
    {
        $now = $now ?? Carbon::now();
        return $now->greaterThanOrEqualTo($this->tanggalEligibilitasPertama($user));
    }

    /**
     * Cek status klaim Kacamata. Periode Kacamata dihitung sebagai SIKLUS 24 BULAN
     * TETAP sejak tanggal eligibilitas pertama (tgl_kartap + 3 bulan, awal bulan) —
     * BUKAN 24 bulan bergulir dari tanggal klaim terakhir. Artinya kapan pun klaim
     * diajukan dalam satu siklus, batas "boleh klaim lagi" selalu di awal siklus
     * berikutnya (bukan +24 bulan dari tanggal submit).
     * Return: ['boleh_klaim' => bool, 'tanggal_boleh_klaim_lagi' => Carbon|null]
     */
    public function statusKacamata(User $user, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();
        $periodeMulai = $this->periodeKacamataMulai($user, $now);

        $sudahAdaKlaimDalamPeriode = BenefitKartap::where('user_id', $user->id)
            ->whereRaw('LOWER(jenis_benefit) = ?', ['kacamata'])
            ->whereRaw('LOWER(status) IN (?, ?, ?, ?, ?)', self::STATUS_DIHITUNG_KACAMATA)
            ->where('created_at', '>=', $periodeMulai)
            ->exists();

        if (!$sudahAdaKlaimDalamPeriode) {
            return ['boleh_klaim' => true, 'tanggal_boleh_klaim_lagi' => null];
        }

        return [
            'boleh_klaim' => false,
            'tanggal_boleh_klaim_lagi' => $periodeMulai->copy()->addMonths(self::KACAMATA_COOLDOWN_BULAN),
        ];
    }

    /**
     * Awal siklus 24 bulan Kacamata yang sedang berjalan, dihitung sejak
     * tanggal eligibilitas pertama (tgl_kartap + 3 bulan, awal bulan).
     */
    public function periodeKacamataMulai(User $user, ?Carbon $now = null): Carbon
    {
        $now = $now ?? Carbon::now();
        $eligibilitasPertama = $this->tanggalEligibilitasPertama($user);

        return $this->anniversaryPeriodeMulai($eligibilitasPertama, self::KACAMATA_COOLDOWN_BULAN, $now);
    }

    /**
     * Awal periode plafon berjalan untuk MCU/Vitamin, berdasarkan siklus 12 bulan
     * sejak tanggal eligibilitas pertama. Reset setiap tahun di tanggal anniversary.
     */
    public function awalPeriodePlafon(User $user, ?Carbon $now = null): Carbon
    {
        $now = $now ?? Carbon::now();
        $eligibilitasPertama = $this->tanggalEligibilitasPertama($user);

        return $this->anniversaryPeriodeMulai($eligibilitasPertama, self::MCU_VITAMIN_SIKLUS_BULAN, $now);
    }

    /**
     * Helper umum: hitung awal siklus ke-berapa yang sedang berjalan, dimulai dari
     * $start, dengan panjang siklus $cycleBulan, relatif terhadap $now.
     * Contoh: start=1 Agu 2024, cycle=24, now=1 Sep 2026
     *         -> siklus ke-1 (1 Agu 2024 - 31 Jul 2026) sudah lewat
     *         -> return 1 Agu 2026 (awal siklus ke-2 yang sedang berjalan)
     */
    private function anniversaryPeriodeMulai(Carbon $start, int $cycleBulan, Carbon $now): Carbon
    {
        if ($now->lessThan($start)) {
            return $start->copy();
        }

        $bulanBerjalan = $start->diffInMonths($now);
        $jumlahSiklusLewat = intdiv($bulanBerjalan, $cycleBulan);

        return $start->copy()->addMonths($jumlahSiklusLewat * $cycleBulan);
    }

    /**
     * Panjang siklus (dalam bulan) untuk jenis benefit tertentu.
     * Kacamata = 24 bulan, MCU/Vitamin = 12 bulan.
     */
    private function siklusBulanUntuk(string $jenisBenefit): int
    {
        return $jenisBenefit === 'Kacamata' ? self::KACAMATA_COOLDOWN_BULAN : self::MCU_VITAMIN_SIKLUS_BULAN;
    }

    /**
     * Level jabatan user, dipakai untuk lookup config('benefit_limit').
     * Sesuaikan path relasi ini dengan struktur data sebenarnya.
     */
    public function levelJabatan(User $user): ?string
    {
        return $user->karyawan?->jabatan?->level;
    }

    /**
     * Ambil limit plafon dari config berdasarkan level jabatan & jenis benefit.
     * $jenisBenefit HARUS persis: 'Kacamata' | 'Vitamin' | 'MCU'
     */
    public function limitPlafon(User $user, string $jenisBenefit): ?int
    {
        $level = $this->levelJabatan($user);
        if (!$level || !isset(self::JENIS_LIST[$jenisBenefit])) {
            return null;
        }

        $limit = config("benefit_limit.$level.$jenisBenefit");

        return $limit !== null ? (int) $limit : null;
    }

    /**
     * Tentukan awal periode yang relevan untuk menghitung pemakaian:
     * - MCU/Vitamin: siklus 12 bulan sejak eligibilitas pertama
     * - Kacamata: siklus 24 bulan sejak eligibilitas pertama
     */
    private function periodeHitung(User $user, string $jenisBenefit, ?Carbon $now = null): ?Carbon
    {
        $now = $now ?? Carbon::now();

        if ($jenisBenefit === 'Kacamata') {
            return $this->periodeKacamataMulai($user, $now);
        }

        return $this->awalPeriodePlafon($user, $now);
    }

    /**
     * Total nominal yang sudah dipakai (status pending atau approved) untuk jenis
     * benefit tertentu dalam periode yang relevan. Klaim rejected tidak dihitung.
     */
    public function totalTerpakai(User $user, string $jenisBenefit, ?Carbon $now = null): int
    {
        $periodeMulai = $this->periodeHitung($user, $jenisBenefit, $now);
        $statusList = $jenisBenefit === 'Kacamata' ? self::STATUS_DIHITUNG_KACAMATA : self::STATUS_DIHITUNG;
        $placeholders = implode(', ', array_fill(0, count($statusList), '?'));

        $query = BenefitKartap::where('user_id', $user->id)
            ->whereRaw('LOWER(jenis_benefit) = ?', [strtolower($jenisBenefit)])
            ->whereRaw("LOWER(status) IN ($placeholders)", $statusList);

        if ($periodeMulai) {
            $query->where('created_at', '>=', $periodeMulai);
        }

        return (int) $query->sum('nominal');
    }

    /**
     * Sisa plafon user untuk jenis benefit & periode berjalan.
     * Return: ['limit' => int|null, 'terpakai' => int, 'sisa' => int|null, 'periode_mulai' => Carbon|null]
     */
    public function sisaPlafon(User $user, string $jenisBenefit, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();
        $limit = $this->limitPlafon($user, $jenisBenefit);
        $periodeMulai = $this->periodeHitung($user, $jenisBenefit, $now);
        $terpakai = $this->totalTerpakai($user, $jenisBenefit, $now);

        return [
            'limit' => $limit,
            'terpakai' => $terpakai,
            'sisa' => $limit !== null ? max(0, $limit - $terpakai) : null,
            'periode_mulai' => $periodeMulai,
        ];
    }

    /**
     * Rekap plafon SEMUA jenis benefit (Kacamata, Vitamin, MCU) untuk ditampilkan
     * di tabel "Sisa Plafon Benefit Anda" pada halaman index.
     *
     * Format tiap item:
     * [
     *   'jenis_benefit'            => 'kacamata',       // label tampilan
     *   'jenis_key'                => 'Kacamata',       // value asli utk DB/config
     *   'jumlah_klaim'             => int,
     *   'total_nominal'            => float,
     *   'plafon'                   => int|null,
     *   'sisa_plafon'              => int|null,
     *   'melebihi_plafon'          => bool|null,
     *   'sudah_diajukan'           => bool,
     *   'periode_mulai'            => Carbon,           // tanggal anniversary siklus berjalan
     *   'periode_reset_berikutnya' => Carbon,           // tanggal anniversary siklus berikutnya
     * ]
     */
    public function rekapPlafon(User $user, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();
        $hasil = [];

        foreach (self::JENIS_LIST as $jenisBenefit => $label) {
            $limit = $this->limitPlafon($user, $jenisBenefit);
            $periodeMulai = $this->periodeHitung($user, $jenisBenefit, $now);
            $siklusBulan = $this->siklusBulanUntuk($jenisBenefit);
            $statusList = $jenisBenefit === 'Kacamata' ? self::STATUS_DIHITUNG_KACAMATA : self::STATUS_DIHITUNG;
            $placeholders = implode(', ', array_fill(0, count($statusList), '?'));

            $query = BenefitKartap::where('user_id', $user->id)
                ->whereRaw('LOWER(jenis_benefit) = ?', [strtolower($jenisBenefit)])
                ->whereRaw("LOWER(status) IN ($placeholders)", $statusList);

            if ($periodeMulai) {
                $query->where('created_at', '>=', $periodeMulai);
            }

            $jumlahKlaim = (clone $query)->count();
            $totalNominal = (float) (clone $query)->sum('nominal');

            $hasil[] = [
                'jenis_benefit' => $label,
                'jenis_key' => $jenisBenefit,
                'jumlah_klaim' => $jumlahKlaim,
                'total_nominal' => $totalNominal,
                'plafon' => $limit,
                'sisa_plafon' => $limit !== null ? max(0, $limit - $totalNominal) : null,
                'melebihi_plafon' => $limit !== null ? $totalNominal > $limit : null,
                'sudah_diajukan' => $jumlahKlaim > 0,
                'periode_mulai' => $periodeMulai,
                'periode_reset_berikutnya' => $periodeMulai->copy()->addMonths($siklusBulan),
            ];
        }

        return $hasil;
    }

    /**
     * Validasi lengkap sebelum submit klaim.
     * $jenisBenefit HARUS persis: 'Kacamata' | 'Vitamin' | 'MCU'
     * Return: ['boleh' => bool, 'pesan' => string|null]
     */
    public function validasiKlaim(User $user, string $jenisBenefit, float $nominal, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();

        if (!$this->sudahEligible($user, $now)) {
            return [
                'boleh' => false,
                'pesan' => 'Klaim benefit baru bisa dilakukan mulai tanggal '
                    . $this->tanggalEligibilitasPertama($user)->translatedFormat('d F Y'),
            ];
        }

        if ($jenisBenefit === 'Kacamata') {
            $status = $this->statusKacamata($user, $now);
            if (!$status['boleh_klaim']) {
                return [
                    'boleh' => false,
                    'pesan' => 'Klaim benefit Kacamata baru bisa diajukan lagi mulai tanggal '
                        . $status['tanggal_boleh_klaim_lagi']->translatedFormat('d F Y'),
                ];
            }

            // Kacamata tetap punya plafon nominal per klaim (bukan akumulasi tahunan)
            $limit = $this->limitPlafon($user, 'Kacamata');
            if ($limit !== null && $nominal > $limit) {
                return [
                    'boleh' => false,
                    'pesan' => 'Nominal melebihi plafon Kacamata (maksimal: Rp '
                        . number_format($limit, 0, ',', '.') . ').',
                ];
            }

            return ['boleh' => true, 'pesan' => null];
        }

        if (in_array($jenisBenefit, ['MCU', 'Vitamin'])) {
            $level = $this->levelJabatan($user);
            if (!$level) {
                return ['boleh' => false, 'pesan' => 'Data jabatan Anda tidak ditemukan, tidak dapat memproses klaim.'];
            }

            $plafon = $this->sisaPlafon($user, $jenisBenefit, $now);
            $jenisLabel = self::JENIS_LIST[$jenisBenefit] ?? $jenisBenefit;

            if ($plafon['limit'] === null) {
                return ['boleh' => false, 'pesan' => "Plafon benefit {$jenisLabel} untuk jabatan Anda tidak ditemukan."];
            }

            if ($plafon['sisa'] <= 0) {
                return [
                    'boleh' => false,
                    'pesan' => "Plafon benefit {$jenisLabel} Anda sudah habis untuk periode berjalan"
                        . ($plafon['periode_mulai'] ? ' (berlaku sejak ' . $plafon['periode_mulai']->translatedFormat('d F Y') . ')' : '')
                        . ".",
                ];
            }

            if ($nominal > $plafon['sisa']) {
                return [
                    'boleh' => false,
                    'pesan' => "Nominal melebihi sisa plafon benefit {$jenisLabel} Anda (sisa: Rp "
                        . number_format($plafon['sisa'], 0, ',', '.') . ").",
                ];
            }

            return ['boleh' => true, 'pesan' => null];
        }

        return ['boleh' => true, 'pesan' => null];
    }

    /**
     * Ringkasan lengkap status eligibilitas & plafon user, untuk ditampilkan
     * di halaman create (info card sebelum submit).
     * Catatan: key array 'kacamata'/'mcu'/'vitamin' di sini cuma untuk kenyamanan
     * penamaan di view — bukan value yang dikirim ke database.
     */
    public function ringkasan(User $user, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();

        return [
            'sudah_eligible' => $this->sudahEligible($user, $now),
            'tanggal_boleh_klaim' => $this->tanggalEligibilitasPertama($user),
            'kacamata' => $this->statusKacamata($user, $now),
            'mcu' => $this->sisaPlafon($user, 'MCU', $now),
            'vitamin' => $this->sisaPlafon($user, 'Vitamin', $now),
        ];
    }
}