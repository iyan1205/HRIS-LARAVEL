@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Absensi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Absensi</a></li>
                        <li class="breadcrumb-item active">Daftar Hadir</li>
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
                            <a href="{{ route('attendance.index') }}" class="btn btn-primary mb-3">Buat Absensi</a>
                            <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#searchModal">
                                Cari Absensi
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal dan Jam Masuk</th>
                                        <th>Tanggal dan Jam Keluar</th>
                                        <th>Total Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendance as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->user->karyawan->name }}</td>
                                        <td>{{ $attendance->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            {{ $attendance->updated_at == $attendance->created_at ? ' ' : $attendance->updated_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td> @if ($attendance->total_duration === 'Tidak absen pulang')
                                            <span class="badge badge-danger">{{ $attendance->total_duration }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $attendance->total_duration }}</span>
                                        @endif</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data kehadiran untuk tanggal ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('attendance.find') }}" method="GET">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Cari Kehadiran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                            <label for="date">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection