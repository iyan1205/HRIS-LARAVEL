@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Approve Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('btn-sc.cuti') }}">Cancel Cuti</a></li>
                            <li class="breadcrumb-item active">Data Cancel Cuti</li>
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
                                                   
                            <div class="card-body ">
                            @if(isset($results) && count($results) > 0)
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Jabatan</th>
                                            <th>Instalasi</th>
                                            <th>Departemen</th>
                                            <th>Kategori Cuti</th>
                                            <th>Jenis Cuti</th>
                                            <th>Tanggal Awal</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Hari</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $cuti)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cuti->karyawan_name }}</td>
                                            <td>{{ $cuti->nama_jabatan }}</td>
                                            <td>{{ $cuti->nama_unit }}</td>
                                            <td>{{ $cuti->nama_departemen }}</td>
                                            <td>{{ $cuti->kategori }}</td>
                                            <td>{{ $cuti->leave_type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}</td>
                                            <td>{{ $cuti->total_days }} Hari</td>
                                            <td class="project-actions text-right">
                                                @can('cancel_approve')
                                                <button type="button" class="btn btn-danger btn-sm rejectBtn" data-cuti-id="{{ $cuti->id }}" data-toggle="modal" data-target="#modal-lg{{ $cuti->id }}"><i class="fas fa-times"></i> Cancel</button>
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
                                                <form id="rejectForm{{ $cuti->id }}" action="{{ route('cuti.cancel_approve', $cuti->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="alasan_reject{{ $cuti->id }}">Alasan Reject:</label>
                                                            <textarea class="form-control" id="alasan_reject{{ $cuti->id }}" name="alasan_reject" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="{{ $cuti->user_id }}">
                                                    <input type="hidden" value="{{ $cuti->status }}">
                                                    <input type="hidden" value="{{ $cuti->leavetype->kategori_cuti }}">
                                                    <input type="hidden" value="{{ $cuti->leave_type_id }}">
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
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Data Tidak Ada!</h5>
                                Pilih Tanggal yang sesuai
                            </div>
                            <a href="{{ route('btn-sc.cuti') }}" class=" btn btn-secondary">Kembali</a>
                            @endif
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
