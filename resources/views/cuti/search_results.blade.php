@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('laporan-cuti') }}">Home</a></li>
                            <li class="breadcrumb-item active">Cuti</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                               
                            </div>
                           
                            <div class="card-body ">
                            @if(isset($results) && count($results) > 0)
                                <table class="table table-bordered table-hover" id="laporan">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Jabatan</th>
                                            <th>Kategori Cuti</th>
                                            <th>Jenis Cuti</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Tanggal Awal</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Hari</th>
                                            <th>Status</th>
                                            <th>Tgl Approve SPV</th>
                                            <th>Nama SPV</th>
                                            <th>Tgl Approve Manajer</th>
                                            @if($status == '' || $status == 'rejected')
                                            <th>Nama Manajer</th>
                                            @endif
                                            @if($status == '' || $status == 'rejected')
                                            <th>Alasan Reject</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $result)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $result->karyawan_name }}</td>
                                            <td>{{ $result->nama_jabatan }}</td>
                                            <td>{{ $result->kategori }}</td>
                                            <td>{{ $result->leave_type }}</td>
                                            <td>{{ $result->created_at }}</td>
                                            <td>{{ $result->start_date }}</td>
                                            <td>{{ $result->end_date }}</td>
                                            <td>{{ $result->total_days }} Hari</td>
                                            <td>{{ $result->status }}</td>
                                            <td>{{ $result->updated_at_atasan }}</td>
                                            <td>{{ $result->updated_by_atasan }}</td>
                                           <td>{{ $result->created_at }}</td>
                                            @if($status == '' || $status == 'rejected')
                                            <td>{{ $result->updated_by }}</td>
                                            @endif
                                            @if($status == '' || $status == 'rejected')
                                            <td>{{ $result->alasan_reject }}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Data Tidak Ada!</h5>
                                Pilih Tanggal yang sesuai
                            </div>
                            <a href="{{ route('laporan-cuti') }}" class=" btn btn-secondary">Kembali</a>
                            @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection
