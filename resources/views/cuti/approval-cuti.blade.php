@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cuti</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jenis/Kategori</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaveApplications as $cuti)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cuti->user->karyawan->name }}</td>
                                        <td>{{ $cuti->leavetype->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}</td>
                                        <td><span class="tag tag-success">{{ $cuti->status }}</span></td>
                                        <td class="project-actions text-right">
                                            @can('approve cuti')
                                            <form id="approveForm{{ $cuti->id }}" action="{{ route('leave-application.approve', $cuti->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm rejectBtn" data-cuti-id="{{ $cuti->id }}" data-toggle="modal" data-target="#modal-lg{{ $cuti->id }}"><i class="fas fa-times"></i> Reject</button>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-lg{{ $cuti->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Reject Pengajuan Cuti</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('leave-application.reject', $cuti->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="alasan_reject{{ $cuti->id }}">Alasan Reject:</label>
                                                            <textarea class="form-control" id="alasan_reject{{ $cuti->id }}" name="alasan_reject" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
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
