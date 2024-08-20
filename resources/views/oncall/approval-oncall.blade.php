@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">On Call</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">On Call</li>
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
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Interval</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oncalls as $oncall)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $oncall->user->karyawan->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($oncall->start_date)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($oncall->end_date)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $oncall->interval }}</td>
                                        <td>{{ $oncall->keterangan }}</td>
                                        <td class="project-actions text-right">
                                            @can('approve cuti')
                                            <button type="button" class="btn btn-success btn-sm approveBtn" data-oncall-id="{{ $oncall->id }}" data-toggle="modal" data-target="#modal-ap{{ $oncall->id }}"><i class="fas fa-check"></i> Approve</button>
                                            <button type="button" class="btn btn-danger btn-sm rejectBtn" data-oncall-id="{{ $oncall->id }}" data-toggle="modal" data-target="#modal-lg{{ $oncall->id }}"><i class="fas fa-times"></i> Reject</button>
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

@foreach ($oncalls as $oncall)
<!-- Modal Approve -->
<div class="modal fade" id="modal-ap{{ $oncall->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Approve Pengajuan On Call</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm{{ $oncall->id }}" action="{{ route('oncall.approve', $oncall->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Yakin Pengajuan oncall <b>{{ $oncall->user->karyawan->name }}</b> akan di Approve  ?</p>
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
<div class="modal fade" id="modal-lg{{ $oncall->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reject Pengajuan On Call</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm{{ $oncall->id }}" action="{{ route('oncall.reject', $oncall->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan_reject{{ $oncall->id }}">Alasan Reject:</label>
                        <textarea class="form-control" id="alasan_reject{{ $oncall->id }}" name="alasan_reject" rows="3" required></textarea>
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
