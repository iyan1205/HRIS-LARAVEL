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
                                    <span class="info-box-text">Jumlah User</span>
                                    <span class="info-box-number">{{ $karyawanCount }}</span>
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
                     <!-- TO DO List -->
                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">
                            <i class="ion ion-clipboard mr-1"></i>
                            Data Cuti Hari ini
                        </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <ul class="todo-list" data-widget="todo-list">
                            @if($leaveApplicationsToday->isEmpty())
                                <p>Tidak Ada Karyawan Cuti Hari ini.</p>
                            @else
                                <table id="allTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Karyawan</th>
                                            <th>NIK</th>
                                            <th>Jabatan</th>
                                            <th>Tanggal Cuti</th>
                                            <th>Total Hari</th>
                                            <th>Jenis</th>
                                            <th>Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaveApplicationsToday as $application)
                                            <tr>
                                                <td>{{ $application->id }}</td>
                                                <td>{{ $application->nama_karyawan }}</td>
                                                <td>{{ $application->nik }}</td>
                                                <td>{{ $application->jabatan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->start_date)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($application->end_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $application->total_days }}</td>
                                                <td>{{ $application->name }}</td>
                                                <td>{{ $application->kategori_cuti }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
              <!-- /.card -->
                </div><!-- /.container-fluid -->
            </section>
        @else
        
        <div class="container-fluid">
            <!-- CUTI -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('pengajuan-cuti') }}">Pengajuan Cuti</a></h5>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-paper-plane"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Disetujui</span>
                            <span class="info-box-number">{{ $pengajuanReject }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" >
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-paper-plane"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Disetujui</span>
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
                            <span class="info-box-text">Belum Disetujui</span>
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
            <!-- LEMBUR -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('overtime') }}">Pengajuan Lembur</a></h5>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fas fa-calendar-plus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Disetujui</span>
                            <span class="info-box-number">{{ $lemburrejected }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fas fa-calendar-plus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Disetujui</span>
                            <span class="info-box-number">{{ $lemburapproved }}</span>
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
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fas fa-calendar-plus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Belum Disetujui</span>
                            <span class="info-box-number">{{ $lemburpending }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fas fa-calendar-plus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Pengajuan</span>
                            <span class="info-box-number">{{ $lemburpending + $lemburrejected + $lemburapproved  }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- ON CALL -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('oncall') }}">Pengajuan On Call</a></h5>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Disetujui</span>
                            <span class="info-box-number">{{ $oncallrejected }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Disetujui</span>
                            <span class="info-box-number">{{ $oncallapproved }}</span>
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
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Belum Disetujui</span>
                            <span class="info-box-number">{{ $oncallpending }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Pengajuan</span>
                            <span class="info-box-number">{{ $oncallpending + $oncallrejected + $oncallapproved  }}</span>
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
