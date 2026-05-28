@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Riwayat Approval Cuti</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('approval-cuti') }}">Approval </a></li>
                            <li class="breadcrumb-item active">Riwayat Approval</li>
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
                                        <th>Tanggal Approval</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvalHistory as $history)
                                            @php $cuti = $history->leaveApplication; @endphp
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
                                            <a href="{{ asset('storage/'. $cuti->file_upload) }}" target="_blank">Lihat Dokumen</a>
                                            @else
                                            {{ 'File tidak tersedia' }}                                                
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($history->updated_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($history->action == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif ($history->action == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif ($history->action == 'escalated')
                                                <span class="badge bg-warning">Diteruskan</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($history->action) }}</span>
                                            @endif
                                        </td>
                                    </tr>
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
