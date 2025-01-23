@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pengajuan Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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
                                <a href="{{ route('cuti.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                                <a href="{{ route('riwayat-cuti') }}" class="btn btn-warning mb-3">Riwayat Pengajuan Cuti</a>
                                
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pengajuan</th>
                                            <th>Nama Karyawan</th>
                                            <th>Kategori</th>
                                            <th>Jenis</th>
                                            <th>Tanggal Cuti</th>
                                            <th>Total Hari</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveApplications as $cuti)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"><b>LA-{{ $cuti->id }}</b></span></td>
                                                <td>{{ $cuti->user->karyawan->name }}</td>
                                                <td>{{ $cuti->leavetype->kategori_cuti }}</td>
                                                <td>{{ $cuti->leavetype->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $cuti->total_days }} Hari</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $cuti->status }}
                                                    </span>
                                                </td>
                                                <td class="project-actions text-right">
                                                    <a data-toggle="modal" data-target="#modal-detail{{ $cuti->id }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                                    @if (is_null($cuti->updated_by))
                                                    <a href="{{ route('cuti.edit', ['id' => $cuti->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> </a>
                                                    @endif
                                                    {{-- <a data-toggle="modal" data-target="#modal-hapus{{ $cuti->id }}" class="btn btn-danger btn-sm" title="Batal"><i class="fas fa-times-circle"></i></a> --}}
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-detail{{ $cuti->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Detail</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="">Diperbarui oleh:</label>
                                                                    <input type="text" class="form-control" value="{{ $cuti->updated_by ?? 'Belum Diperbarui Atasan' }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Diperbarui pada:</label>
                                                                    <input type="text" class="form-control" value="{{ $cuti->updated_at == $cuti->created_at ? 'Belum Diperbarui ' : \Carbon\Carbon::parse($cuti->updated_at)->format('d/m/Y H:i:s') }}" readonly>
                                                                </div>
                                                                <div>
                                                                    <label for="file_upload">Dokumen Pendukung</label>
                                                                    @if ( $cuti->file_upload)
                                                                    <a href="{{ asset('storage/'. $cuti->file_upload) }}" class="form-control" target="_blank">Lihat Dokumen</a></td>
                                                                    @else
                                                                    <input class="form-control" readonly value="File tidak tersedia">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modal-hapus{{ $cuti->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Konfirmasi Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah kamu yakin ingin <b>Membatalkan</b> Pengajuan Cuti Ini ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <form action="{{ route('leave-application.cancel', ['id' => $cuti->id]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: -300px">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Ya, Batal
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                <!-- /.modal-dialog -->
                                        @endforeach
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
@endsection
