@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Riwayat Cuti: <b>{{ $user->karyawan->name ?? 'Admin'}}</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pengajuan-cuti') }}">Cuti </a></li>
                            <li class="breadcrumb-item active">Riwayat Cuti</li>
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
                            
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pengajuan</th>
                                            <th>Kategori</th>
                                            <th>Jenis</th>
                                            <th>Maksimal Cuti</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Hari</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveApplications as $cuti)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"> <a href="" data-toggle="modal" data-target="#modal-detail{{ $cuti->id }}"><b>LA-{{ $cuti->id }}</b></a></span></td>
                                                <td>{{ $cuti->leavetype->kategori_cuti }}</td>
                                                <td>{{ $cuti->leavetype->name }}</td>
                                                <td> @if (is_numeric($cuti->leavetype->max_amount) && $cuti->leavetype->max_amount != '-')
                                                    <span class="text-danger font-weight-bold">{{ $cuti->leavetype->max_amount }} Hari</span>
                                                @else
                                                    -
                                                @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $cuti->total_days }} Hari</td>
                                                <td>
                                                    @if($cuti->status == 'rejected')
                                                        <span class="badge bg-danger">
                                                            <a href="" title="Alasan Reject" data-toggle="modal" data-target="#modal-lg{{ $cuti->id }}">{{ $cuti->status }}</a>
                                                        </span>
                                                    @elseif($cuti->status == 'approved')
                                                        <span class="badge bg-success">{{ $cuti->status }}</span>
                                                    @else()
                                                        <span class="badge bg-warning">{{ $cuti->status }}</span>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                            <div class="modal fade" id="modal-lg{{ $cuti->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Detail Status</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="">Updated by</label>
                                                                    <input type="text" class="form-control" value="{{ $cuti->updated_by }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Updated at</label>
                                                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cuti->updated_at)->format('d/m/Y H:i:s') }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="alasan_reject">Alasan Reject</label>
                                                                    <textarea class="form-control" id="alasan_reject{{ $cuti->id }}" name="alasan_reject" rows="3" disabled> {{ $cuti->alasan_reject }}</textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                             <div class="modal fade" id="modal-detail{{ $cuti->id }}">
                                                <div class="modal-dialog modal-detail">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Detail </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="">Updated by</label>
                                                                    <input type="text" class="form-control" value="{{ $cuti->updated_by }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Updated at</label>
                                                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cuti->updated_at)->format('d/m/Y H:i:s') }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="file_upload">File Cuti</label>
                                                                    @if ($cuti->file_upload)
                                                                        <a href="{{ Storage::url($cuti->file_upload) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                            <i class="fas fa-file-download"></i> Download
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted">Tidak ada file</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
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
