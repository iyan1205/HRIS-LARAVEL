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
                            <li class="breadcrumb-item"><a href="{{ route('benefit-kartap.index') }}">Benefit Kartap</a></li>
                            <li class="breadcrumb-item active">Data Belum Lengkap</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-danger card-outline">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>

                                <h3 class="mt-4 mb-2">Tanggal Kartap Belum Diisi</h3>

                                <p class="text-muted mb-4">
                                    {{ $pesan }}
                                </p>

                                <p class="text-muted small mb-4">
                                    Silakan hubungi Admin SDM untuk melengkapi data <strong>Tanggal Pengangkatan Kartap</strong>
                                    Anda di sistem sebelum dapat mengajukan benefit kartap.
                                </p>

                                <a href="{{ route('benefit-kartap.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengajuan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection