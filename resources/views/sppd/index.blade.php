@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">SPPD</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">SPPD</li>
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
                                <a href="{{ route('sppd.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                                <a href="{{ route('riwayat-sppd') }}" class="btn btn-warning mb-3">Riwayat Pengajuan sppd</a>
                                
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
                                            <th>Tujuan Kota/Negara</th>
                                            <th>Rencana Kegiatan</th>
                                            <th>Tanggal Berangkat</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sppds as $sppd)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"><b>SPPD-{{ $sppd->id }}</b></span></td>
                                                <td>{{ $sppd->user->karyawan->name }}</td>
                                                <td>{{ $sppd->kategori_dinas }}</td>
                                                <td>{{ $sppd->kota_tujuan }},{{ $sppd->negara_tujuan }}</td>
                                                <td>{{ $sppd->rencana_kegiatan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d/m/Y H:i') }}
                                                <td>{{ \Carbon\Carbon::parse($sppd->tanggal_kembali)->format('d/m/Y H:i') }}
                                                <td class="project-actions text-right">
                                                    <a data-toggle="modal" data-target="#modal-detail{{ $sppd->id }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                                    @if (is_null($sppd->updated_by))
                                                    <a href="{{ route('sppd.edit', ['id' => $sppd->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i> </a>
                                                    @endif
                                                    {{-- <a data-toggle="modal" data-target="#modal-hapus{{ $sppd->id }}" class="btn btn-danger btn-sm" title="Batal"><i class="fas fa-times-circle"></i></a> --}}
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-detail{{ $sppd->id }}">
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
                                                                    <label for="">Status:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->status }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Diperbarui oleh:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->updated_by ?? 'Belum Diperbarui Atasan' }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Diperbarui pada:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->updated_at == $sppd->created_at ? 'Belum Diperbarui ' : \Carbon\Carbon::parse($sppd->updated_at)->format('d/m/Y H:i:s') }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Fasilitas Kendaraan:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->fasilitas_kendaraan }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Fasilitas Transportasi:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->fasilitas_transportasi }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Fasilitas Akomodasi:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->fasilitas_akomodasi }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Biaya:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->cost }}" readonly> 
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modal-hapus{{ $sppd->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Konfirmasi Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah kamu yakin ingin <b>Membatalkan</b> Pengajuan sppd Ini ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <form action="{{ route('leave-application.cancel', ['id' => $sppd->id]) }}" method="POST">
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
