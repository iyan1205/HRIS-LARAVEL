@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @role('Super-Admin|admin')
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-minus"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan Resign</span>
                                    <span class="info-box-number">{{ $jumlahKaryawanResign }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan Active</span>
                                    <span class="info-box-number">{{ $jumlahKaryawanAktif }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
    
                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>
    
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Karyawan</span>
                                    <span class="info-box-number">{{ $jumlahKaryawanAktif + $jumlahKaryawanResign }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-paper-plane"></i></span>
    
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengajuan Cuti</span>
                                    <span class="info-box-number">{{ $pengajuanCuti }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
    
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
        @else
        
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-paper-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pengajuan Tidak Disetujui</span>
                                <span class="info-box-number">{{ $pengajuanReject }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-paper-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pengajuan Disetujui</span>
                                <span class="info-box-number">{{ $pengajuanApproved }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-paper-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pengajuan Belum Disetujui</span>
                                <span class="info-box-number">{{ $pengajuanCuti }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-paper-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Jumlah Pengajuan</span>
                                <span class="info-box-number">{{ $pengajuanReject + $pengajuanApproved + $pengajuanCuti  }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>

            </div><!-- /.container-fluid -->
        
        @endrole
        <!-- /.content -->
        
    </div>
@endsection
