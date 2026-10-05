@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Benefit Kartap</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Benefit Kartap</a></li>
                            <li class="breadcrumb-item active">Form Benefit Kartap</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
       <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- left column -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title w-100 text-uppercase font-weight-bold">Syarat / Dokumen </h3>
                            </div>
                           <div class="card-body text-center">

                                @if ($sudahBolehKlaim)
                                    <h5 class="text-success mb-0">
                                        ✅ Anda sudah dapat mengajukan klaim benefit.
                                    </h5>
                                @else
                                    <p class="mb-2">Klaim dapat dilakukan mulai:</p>
                                    <h5 class="mb-3">{{ $tanggalBolehKlaim->translatedFormat('d F Y') }}</h5>

                                    <div id="countdown" class="d-flex justify-content-center gap-4"
                                        data-target="{{ $tanggalBolehKlaim->toIso8601String() }}">
                                        <div>
                                            <div class="fs-2 fw-bold" id="cd-hari">00</div>
                                            <small>Hari</small>
                                        </div>
                                        <div>
                                            <div class="fs-2 fw-bold" id="cd-jam">00</div>
                                            <small>Jam</small>
                                        </div>
                                        <div>
                                            <div class="fs-2 fw-bold" id="cd-menit">00</div>
                                            <small>Menit</small>
                                        </div>
                                        <div>
                                            <div class="fs-2 fw-bold" id="cd-detik">00</div>
                                            <small>Detik</small>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <!-- /.card-body eligibilitas -->

                            @if(isset($rekapPlafon))
                            <div class="card-body border-top pt-3">
                                <h6 class="text-uppercase text-center mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                    Periode Plafon Berjalan
                                </h6>

                                <div class="row">
                                    @foreach($rekapPlafon as $item)
                                        <div class="col-4 text-center mb-2">
                                            <div class="border rounded p-2 h-100">
                                                <div class="font-weight-bold small text-uppercase mb-1">
                                                    {{ $item['jenis_benefit'] }}
                                                </div>
                                                <div class="text-success" style="font-size: 1rem;">
                                                    {{ $item['periode_mulai']->translatedFormat('d M Y') }}
                                                    &ndash;
                                                    {{ $item['periode_reset_berikutnya']->copy()->subDay()->translatedFormat('d M Y') }}
                                                </div>
                                                <span class="badge badge-light mt-1" style="font-size: 0.7rem;">
                                                    Reset {{ $item['periode_reset_berikutnya']->translatedFormat('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="card-body border-top">
                                <!-- Form Pengajuan Klaim -->
                                <div class="d-flex mb-3">
                                    <i class="fas fa-file-alt fa-2x text-primary mr-3"></i>
                                    <div>
                                        <strong>Form Pengajuan Klaim</strong>
                                        <p class="mb-0 small">
                                            lengkap yang sudah ditandatangani ybs, dan atasan.
                                            <br>
                                            (Formulir dapat didapatkan di Departemen SDM)
                                        </p>
                                    </div>
                                </div>

                                <!-- Bukti Pemeriksaan -->
                                <div class="d-flex mb-3">
                                    <i class="fas fa-file-medical fa-2x text-primary mr-3"></i>
                                    <div>
                                        <strong>Bukti Pemeriksaan / Resume dokter & Resep Dokter</strong>
                                        <p class="mb-0 small">
                                            (jika ada obat yang diresepkan)
                                        </p>
                                    </div>
                                </div>

                                <!-- Kuitansi Bayar -->
                                <div class="d-flex mb-3">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-primary mr-3"></i>
                                    <div>
                                        <strong>Kuitansi Bayar</strong>
                                        <p class="mb-0 small">
                                            dilengkapi tandatangan dan cap instalasi
                                        </p>
                                    </div>
                                </div>

                                <!-- NOTED -->
                                <div class="alert alert-light border mt-3 mb-0">
                                    <strong>NOTED:</strong>
                                    <p class="mb-0 small text-red">
                                        Sebelum mengajukan pada sistem HRIS, karyawan wajib melakukan koordinasi dengan SDM terkait kelengkapan dokumennya sudah sesuai atau belum.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.left column -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title w-100 text-uppercase font-weight-bold">Jenis Benefit & Plafon</h3>
                            </div>
                            <div class="card-body">

                                <!-- KACAMATA -->
                                <div class="benefit-item mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-glasses fa-2x text-primary mr-3"></i>
                                        <div>
                                            <h5 class="mb-0 font-weight-bold">KACAMATA</h5>
                                            <small class="text-muted">(Frame + Lensa)</small>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled pl-4 mb-0">
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>Manajer</span>
                                            <span>1.000.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>SPV & Kepala Instalasi</span>
                                            <span>1.000.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-1">
                                            <span>Staf Pelaksana</span>
                                            <span>1.000.000</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- VITAMIN -->
                                <div class="benefit-item mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-prescription-bottle fa-2x text-primary mr-3"></i>
                                        <h5 class="mb-0 font-weight-bold">VITAMIN</h5>
                                    </div>
                                    <ul class="list-unstyled pl-4 mb-0">
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>Manajer</span>
                                            <span>500.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>SPV & Kepala Instalasi</span>
                                            <span>250.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-1">
                                            <span>Staf Pelaksana</span>
                                            <span>125.000</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- MCU -->
                                <div class="benefit-item">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-heartbeat fa-2x text-primary mr-3"></i>
                                        <h5 class="mb-0 font-weight-bold">MCU</h5>
                                    </div>
                                    <ul class="list-unstyled pl-4 mb-0">
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>Manajer</span>
                                            <span>1.500.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>SPV & Kepala Instalasi</span>
                                            <span>750.000</span>
                                        </li>
                                        <li class="d-flex justify-content-between py-1">
                                            <span>Staf Pelaksana</span>
                                            <span>375.000</span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.right column -->

                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
     @if (!$sudahBolehKlaim)
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdownEl = document.getElementById('countdown');
        const targetDate = new Date(countdownEl.dataset.target).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance <= 0) {
                document.getElementById('cd-hari').textContent = '00';
                document.getElementById('cd-jam').textContent = '00';
                document.getElementById('cd-menit').textContent = '00';
                document.getElementById('cd-detik').textContent = '00';
                clearInterval(interval);
                // reload halaman biar status "sudahBolehKlaim" ke-update dari server
                location.reload();
                return;
            }

            const hari = Math.floor(distance / (1000 * 60 * 60 * 24));
            const jam = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const menit = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const detik = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('cd-hari').textContent = String(hari).padStart(2, '0');
            document.getElementById('cd-jam').textContent = String(jam).padStart(2, '0');
            document.getElementById('cd-menit').textContent = String(menit).padStart(2, '0');
            document.getElementById('cd-detik').textContent = String(detik).padStart(2, '0');
        }

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);
    });
    </script>
    @endif
@endsection
