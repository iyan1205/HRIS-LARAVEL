@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pengajuan Benefit Kartap</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Benefit Kartap</li>
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
                            
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Jenis Benefit</th>
                                            <th>Form Pengajuan</th>
                                            <th>Resume</th>
                                            <th>Bukti Pembayaran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($benefitKartaps as $benefitKartap)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $benefitKartap->user->karyawan->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($benefitKartap->created_at)->format('d/m/Y') }}</td>
                                                <td>{{ strtoupper($benefitKartap->jenis_benefit) }}</td>
                                                <td><a href="{{ asset('storage/' . $benefitKartap->form_pengajuan) }}" target="_blank" >Lihat Form</a></td>
                                                <td><a href="{{ asset('storage/' . $benefitKartap->resume) }}" target="_blank" >Lihat Resume</a></td>
                                                <td><a href="{{ asset('storage/' . $benefitKartap->bukti_pembayaran) }}" target="_blank" >Lihat Bukti</a></td>
                                                <td><span class="badge bg-secondary">{{ $benefitKartap->status }}</span></td>
                                                <td class="project-actions text-right">
                                                    @can('approve benefit-kartap')
                                                    <button type="button" class="btn btn-success btn-sm approveBtn" data-benefitKartap-id="{{ $benefitKartap->id }}" data-toggle="modal" data-target="#modal-ap{{ $benefitKartap->id }}"><i class="fas fa-check"></i> Approve</button>
                                                    @endcan
                                                    @can('reject benefit-kartap')
                                                    <button type="button" class="btn btn-danger btn-sm rejectBtn" data-benefitKartap-id="{{ $benefitKartap->id }}" data-toggle="modal" data-target="#modal-lg{{ $benefitKartap->id }}"><i class="fas fa-times"></i> Reject</button>
                                                    @endcan
                                                    
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modal-ap{{ $benefitKartap->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Approve Pengajuan Benefit Kartap</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form id="approveForm{{ $benefitKartap->id }}" action="{{ route('benefit-kartap.approve', $benefitKartap->id) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <p>Apakah Yakin Pengajuan Benefit Kartap <b> {{ $benefitKartap->user->karyawan->name }} </b> akan di Approve  ?</p>
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
                                            <div class="modal fade" id="modal-lg{{ $benefitKartap->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Reject Pengajuan Benefit Kartap</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form id="rejectForm{{ $benefitKartap->id }}" action="{{ route('benefit-kartap.reject', $benefitKartap->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="alasan_reject{{ $benefitKartap->id }}">Alasan Reject:</label>
                                                                    <textarea class="form-control" id="alasan_reject{{ $benefitKartap->id }}" name="alasan_reject" rows="3" maxlength="500" required></textarea>
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
                                            <div class="modal fade" id="modal-detail{{ $benefitKartap->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Detail Persetujuan</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">{{ $benefitKartap->approval1By->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approval1By->karyawan->name ?? 'Belum Disetujui SPV SDM' }}" readonly>
                                                                        @if($benefitKartap->approval_1_at)
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text bg-success text-white">
                                                                                    <i class="fas fa-check"></i>
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Diperbarui pada:</label>
                                                                    <input type="text" class="form-control" value="{{ $benefitKartap->approval_1_at ? \Carbon\Carbon::parse($benefitKartap->approval_1_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">{{ $benefitKartap->approval2By->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approval2By->karyawan->name ?? 'Belum Disetujui Manager SDM' }}" readonly>
                                                                        @if($benefitKartap->approval_2_at)
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text bg-success text-white">
                                                                                    <i class="fas fa-check"></i>
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div> 
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Diperbarui pada:</label>
                                                                    <input type="text" class="form-control" value="{{ $benefitKartap->approval_2_at ? \Carbon\Carbon::parse($benefitKartap->approval_2_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">{{ $benefitKartap->approvedBy->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approvedBy->karyawan->name ?? 'Belum Disetujui Atasan' }}" readonly>
                                                                        @if($benefitKartap->approved_at)
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text bg-success text-white">
                                                                                    <i class="fas fa-check"></i>
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Diperbarui pada:</label>
                                                                    <input type="text" class="form-control" value="{{ $benefitKartap->approved_at ? \Carbon\Carbon::parse($benefitKartap->approved_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
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
