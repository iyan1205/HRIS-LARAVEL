@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Approval Cuti</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Approval Cuti</li>
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
                        @can('cancel_approve')
                        <div class="card-header">
                            <a href="{{ route('btn-sc.cuti') }}" class="btn btn-danger mb-3">Cancel Approve</a>
                        </div>
                        @endcan
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Nama Karyawan</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Maksimal Cuti</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Mulai/Akhir</th>
                                        <th>Total Hari</th>
                                        <th>Dokumen Pendukung</th>
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
                                        <td> @if (is_numeric($cuti->leavetype->max_amount) && $cuti->leavetype->max_amount != '-')
                                            <span class="text-danger font-weight-bold">{{ $cuti->leavetype->max_amount }} Hari</span>
                                        @else
                                            -
                                        @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($cuti->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}
                                            @if($cuti->start_date != $cuti->end_date)
                                                s.d. {{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        
                                        <td>{{ $cuti->total_days }} Hari</td>
                                        <td>
                                            @if ( $cuti->file_upload)
                                            <a href="{{ asset('storage/'. $cuti->file_upload) }}" target="_blank">Lihat Dokumen</a></td>
                                            @else
                                            {{ 'File tidak tersedia' }}                                                
                                            @endif
                                        <td class="project-actions text-right">
                                            @can('approve cuti')
                                            <button type="button" class="btn btn-success btn-sm approveBtn" data-cuti-id="{{ $cuti->id }}" data-toggle="modal" data-target="#modal-ap{{ $cuti->id }}"><i class="fas fa-check"></i> Approve</button>
                                            <button type="button" class="btn btn-danger btn-sm rejectBtn" data-cuti-id="{{ $cuti->id }}" data-toggle="modal" data-target="#modal-lg{{ $cuti->id }}"><i class="fas fa-times"></i> Reject</button>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-ap{{ $cuti->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Approve Pengajuan Cuti</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="approveForm{{ $cuti->id }}" action="{{ route('leave-application.approve', $cuti->id) }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <p>Apakah Yakin Pengajuan Cuti <b> {{ $cuti->user->karyawan->name }} </b> akan di Approve  ?</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Ya, Approve</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    <div class="modal fade" id="modal-lg{{ $cuti->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Reject Pengajuan Cuti</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="rejectForm{{ $cuti->id }}" action="{{ route('leave-application.reject', $cuti->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="alasan_reject{{ $cuti->id }}">Alasan Reject:</label>
                                                            <textarea class="form-control" id="alasan_reject{{ $cuti->id }}" name="alasan_reject" rows="3" maxlength="500" required></textarea>
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
