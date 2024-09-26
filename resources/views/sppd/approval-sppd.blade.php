@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Approval SSPD</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Approval SPPD</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Nama Karyawan</th>
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
                                        <td style="text-align: center;"> <span class="badge bg-info"><b>OT-{{ $sppd->id }}</b></span></td>
                                        <td>{{ $sppd->user->karyawan->name }}</td>
                                        <td>{{ $sppd->kategori_dinas }}</td>
                                        <td>{{ $sppd->lokasi_tujuan }}</td>
                                        <td>{{ $sppd->rencana_kegiatan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d/m/Y H:i') }}
                                        <td>{{ \Carbon\Carbon::parse($sppd->tanggal_kembali)->format('d/m/Y H:i') }}
                                        <td class="project-actions text-right">
                                            @can('approve cuti')
                                            <a data-toggle="modal" data-target="#modal-detail{{ $sppd->id }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                            <button type="button" class="btn btn-success btn-sm approveBtn" data-sppd-id="{{ $sppd->id }}" data-toggle="modal" data-target="#modal-ap{{ $sppd->id }}"><i class="fas fa-check"></i> Approve</button>
                                            <button type="button" class="btn btn-danger btn-sm rejectBtn" data-sppd-id="{{ $sppd->id }}" data-toggle="modal" data-target="#modal-lg{{ $sppd->id }}"><i class="fas fa-times"></i> Reject</button>
                                            @endcan
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
                                                                <input type="text" class="form-control" value="Rp {{ number_format($sppd->biaya_transfortasi, 0, ',', '.') ?? '0' }} " readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col">
                                                                <label for="">Fasilitas Akomodasi:</label>
                                                                <input type="text" class="form-control" value="{{ $sppd->fasilitas_akomodasi ?? '-' }}" readonly> 
                                                            </div>
                                                            <div class="col">
                                                                <label for="">Biaya Akomodasi:</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($sppd->biaya_akomodasi, 0, ',', '.') ?? '0' }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col">
                                                                <label for="">Biaya Pendaftaran:</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($sppd->biaya_pendaftaran, 0, ',', '.') ?? '0' }}" readonly> 
                                                            </div>
                                                            <div class="col">
                                                                <label for="">Biaya Uang Saku:</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($sppd->biaya_uangsaku, 0, ',', '.') ?? '0' }}" readonly> 
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
    </section><!-- /.content -->
</div>

@foreach ($sppds as $sppd)
<!-- Modal Approve -->
<div class="modal fade" id="modal-ap{{ $sppd->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Approve SPPD</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm{{ $sppd->id }}" action="{{ route('sppd.approve', $sppd->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Yakin SPPD <b>{{ $sppd->user->karyawan->name }}</b> akan di Approve  ?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modal-lg{{ $sppd->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reject SPPD</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm{{ $sppd->id }}" action="{{ route('sppd.reject', $sppd->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan_reject{{ $sppd->id }}">Alasan Reject:</label>
                        <textarea class="form-control" id="alasan_reject{{ $sppd->id }}" name="alasan_reject" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Ya, Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    // Script untuk menangani pengiriman formulir ketika tombol "Ya, Approve" diklik
    $(document).on('click', '.approveBtn', function() {
        var sppdId = $(this).data('sppd-id');
        $('#approveForm' + sppdId).submit();
    });
</script>
@endsection
