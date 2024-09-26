@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Riwayat SPPD: <b>{{ $user->karyawan->name ?? 'Admin'}}</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sppd') }}">SPPD</a></li>
                            <li class="breadcrumb-item active">Riwayat SPPD </li>
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
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pengajuan</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Lokasi Tujuan</th>
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
                                                <td style="text-align: center;">
                                                    @if($sppd->status == 'rejected')
                                                    <span class="badge bg-danger">
                                                        <b>SPPD-{{ $sppd->id }}</b>
                                                    </span>
                                                @elseif($sppd->status == 'approved')
                                                    <span class="badge bg-success"><b>SPPD-{{ $sppd->id }}</b></span>
                                                @endif</td>
                                                <td>{{ $sppd->created_at }}</td>
                                                <td>{{ $sppd->kategori_dinas }}</td>
                                                <td>{{ $sppd->lokasi_tujuan }}</td>
                                                <td>{{ $sppd->rencana_kegiatan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d/m/Y H:i') }}
                                                <td>{{ \Carbon\Carbon::parse($sppd->tanggal_kembali)->format('d/m/Y H:i') }}
                                                <td class="project-actions text-right">
                                                    <a data-toggle="modal" data-target="#modal-detail{{ $sppd->id }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
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
                                                                <div class="form-group row">
                                                                    <div class="col">
                                                                        <label for="">Status:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->status }}" readonly>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Diperbarui oleh:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->updated_by ?? 'Belum Diperbarui Atasan' }}" readonly> 
                                                                    </div>
                                                                     <div class="col">
                                                                        <label for="">Diperbarui pada:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->updated_at == $sppd->created_at ? 'Belum Diperbarui ' : \Carbon\Carbon::parse($sppd->updated_at)->format('d/m/Y H:i:s') }}" readonly>
                                                                     </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Fasilitas Kendaraan:</label>
                                                                    <input type="text" class="form-control" value="{{ $sppd->fasilitas_kendaraan ?? '-' }}" readonly> 
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col">
                                                                        <label for="">Fasilitas Transportasi:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->fasilitas_transportasi ?? '-' }}" readonly> 
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Biaya Transportasi:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->biaya_transfortasi ?? '0' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col">
                                                                        <label for="">Fasilitas Akomodasi:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->fasilitas_akomodasi ?? '-' }}" readonly> 
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Biaya Akomodasi:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->biaya_akomodasi ?? '0' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col">
                                                                        <label for="">Biaya Pendaftaran:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->biaya_pendaftaran ?? '0' }}" readonly> 
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Biaya Uang Saku:</label>
                                                                        <input type="text" class="form-control" value="{{ $sppd->biaya_uangsaku ?? '0' }}" readonly> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>
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
