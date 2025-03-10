@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Approval Lembur</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Approval Lembur</li>
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
                        @can('cancel_approve')
                        <div class="card-header">
                            <a href="{{ route('overtime.sl') }}" class="btn btn-danger mb-3">Cancel Approve</a>
                        </div>
                        @endcan
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Total Jam</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($overtimes as $overtime)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td style="text-align: center;"> <span class="badge bg-info"><b>OT-{{ $overtime->id }}</b></span></td>
                                        <td>{{ $overtime->user->karyawan->name ?? 'Kosong' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->start_date)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->end_date)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $overtime->interval }}</td>
                                        <td>{{ $overtime->keterangan }}</td>
                                        <td class="project-actions text-right">
                                            @can('approve cuti')
                                            <button type="button" class="btn btn-success btn-sm approveBtn" data-overtime-id="{{ $overtime->id }}" data-toggle="modal" data-target="#modal-ap{{ $overtime->id }}"><i class="fas fa-check"></i> Approve</button>
                                            <button type="button" class="btn btn-danger btn-sm rejectBtn" data-overtime-id="{{ $overtime->id }}" data-toggle="modal" data-target="#modal-lg{{ $overtime->id }}"><i class="fas fa-times"></i> Reject</button>
                                            @endcan
                                        </td>
                                    </tr>
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

@foreach ($overtimes as $overtime)
<!-- Modal Approve -->
<div class="modal fade" id="modal-ap{{ $overtime->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Approve Pengajuan Lembur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm{{ $overtime->id }}" action="{{ route('overtime.approve', $overtime->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Yakin Pengajuan overtime <b>{{ $overtime->user->karyawan->name ?? 'Kosong' }}</b> akan di Approve  ?</p>
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
<div class="modal fade" id="modal-lg{{ $overtime->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reject Pengajuan Lembur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm{{ $overtime->id }}" action="{{ route('overtime.reject', $overtime->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan_reject{{ $overtime->id }}">Alasan Reject:</label>
                        <textarea class="form-control" id="alasan_reject{{ $overtime->id }}" name="alasan_reject" rows="3" maxlength="500" required></textarea>
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
@endsection
