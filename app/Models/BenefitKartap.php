<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BenefitKartap extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nominal',
        'jenis_benefit',
        'form_pengajuan',
        'resume',
        'bukti_pembayaran',
        'status',
        'alasan_reject',
        'approval_1_by',
        'approval_2_by',
        'approved_by',
        'approval_1_at',
        'approval_2_at',
        'approved_at'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'approval_1_at' => 'datetime',
        'approval_2_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $hidden = [
        'timestamps',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approval1By()
    {
        return $this->belongsTo(User::class, 'approval_1_by');
    }

    public function approval2By()
    {
        return $this->belongsTo(User::class, 'approval_2_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public static function getPlafon(string $level, string $jenisBenefit): ?float
    {
        return config("benefit_limit.{$level}.{$jenisBenefit}");
    }
    /**
     * Rekap total nominal per karyawan per jenis benefit, HANYA status approved,
     * dihitung per TAHUN (Januari - Desember).
     *
     * @param  int|null  $tahun  Tahun yang dihitung, default tahun berjalan
     * @return \Illuminate\Support\Collection
     */
    public static function totalNominalPerKaryawan(?int $tahun = null)
    {
        $tahun = $tahun ?? now()->year;

        $startDate = "{$tahun}-01-01 00:00:00";
        $endDate   = "{$tahun}-12-31 23:59:59";

        $query = self::query()
            ->select(
                'benefit_kartaps.user_id',
                'users.name as nama_karyawan',
                'jabatans.level',
                'benefit_kartaps.jenis_benefit',
                DB::raw('SUM(benefit_kartaps.nominal) as total_nominal'),
                DB::raw('COUNT(*) as jumlah_klaim')
            )
            ->join('users', 'users.id', '=', 'benefit_kartaps.user_id')
            ->join('karyawans', 'karyawans.user_id', '=', 'users.id')
            ->join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->where('benefit_kartaps.status', 'approved')
            ->whereBetween('benefit_kartaps.approved_at', [$startDate, $endDate])
            ->groupBy(
                'benefit_kartaps.user_id',
                'users.name',
                'jabatans.level',
                'benefit_kartaps.jenis_benefit'
            );

        return $query->get()->map(function ($item) use ($tahun) {
            $plafon = self::getPlafon($item->level, $item->jenis_benefit);
            $total  = (float) $item->total_nominal;

            return [
                'tahun'           => $tahun,
                'user_id'         => $item->user_id,
                'nama_karyawan'   => $item->nama_karyawan,
                'level'           => $item->level,
                'jenis_benefit'   => $item->jenis_benefit,
                'jumlah_klaim'    => (int) $item->jumlah_klaim,
                'total_nominal'   => $total,
                'plafon'          => $plafon,
                'melebihi_plafon' => $plafon !== null ? $total > $plafon : null,
                'sisa_plafon'     => $plafon !== null ? max($plafon - $total, 0) : null,
            ];
        });
    }

    /**
     * Rekap SEMUA jenis benefit untuk level karyawan tertentu, tahun berjalan,
     * baik yang SUDAH diajukan maupun BELUM PERNAH diajukan.
     * Kalau belum pernah diajukan -> total_nominal = 0, sisa_plafon = plafon penuh.
     *
     * @param  string    $userId
     * @param  string    $level
     * @param  int|null  $tahun
     * @return \Illuminate\Support\Collection
     */
    public static function rekapSemuaBenefit(string $userId, string $level, ?int $tahun = null)
    {
        $tahun = $tahun ?? now()->year;

        // 1. Data yang SUDAH pernah diajukan (approved), key by jenis_benefit
        $sudahDiajukan = self::totalNominalByUser($userId, $tahun)
            ->keyBy('jenis_benefit');

        // 2. Semua jenis benefit yang tersedia untuk level ini (dari config)
        $semuaJenisBenefit = array_keys(config("benefit_limit.{$level}", []));

        // 3. Gabungkan: kalau ada di $sudahDiajukan pakai datanya, kalau tidak buat default kosong
        return collect($semuaJenisBenefit)->map(function ($jenis) use ($sudahDiajukan, $level) {
            if ($sudahDiajukan->has($jenis)) {
                return $sudahDiajukan->get($jenis);
            }

            $plafon = self::getPlafon($level, $jenis);

            return [
                'jenis_benefit'   => $jenis,
                'jumlah_klaim'    => 0,
                'total_nominal'   => 0.0,
                'plafon'          => $plafon,
                'melebihi_plafon' => false,
                'sisa_plafon'     => $plafon,
                'sudah_diajukan'  => false, // penanda belum pernah diambil
            ];
        })->map(function ($item) {
            // pastikan semua item punya key 'sudah_diajukan', termasuk yang dari $sudahDiajukan
            $item['sudah_diajukan'] = $item['sudah_diajukan'] ?? true;
            return $item;
        })->values();
    }
    /**
     * Rekap khusus untuk satu karyawan tertentu, per tahun.
     */
    public static function totalNominalByUser(string $userId, ?int $tahun = null)
    {
        return self::totalNominalPerKaryawan($tahun)
            ->where('user_id', $userId)
            ->values();
    }

}

