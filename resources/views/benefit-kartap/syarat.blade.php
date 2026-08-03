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
                <form action="{{ route('benefit-kartap.store') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="row">

                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="card card-primary card-outline">
                                <div class="card-header bg-primary text-center">
                                    <h3 class="card-title w-100 text-uppercase font-weight-bold">Syarat / Dokumen </h3>
                                </div>
                                <div class="card-body">

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
                                        <p class="mb-0 small">
                                            Sebelum mengajukan pada sistem HRIS, karyawan wajib melakukan koordinasi dengan SDM terkait kelengkapan dokumennya sudah sesuai atau belum.
                                        </p>
                                    </div>

                                </div>
                            </div>
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
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
    </div>
   
@endsection
