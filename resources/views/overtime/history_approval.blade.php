@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Riwayat Approval Lembur</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('approval-overtime') }}">Approval Lembur</a></li>
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
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Total Jam</th>
                                        <th>Tanggal Approval</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvalHistory as $history)
                                            @php $overtime = $history->overtime; @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td style="text-align: center;"> <span class="badge bg-info"><b>LO-{{ $overtime->id }}</b></span></td>
                                        <td>{{ $overtime->user->karyawan->name }}</td>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->start_date)->format('d/m/Y h:i:s') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($overtime->end_date)->format('d/m/Y h:i:s') }}</td>
                                        <td>{{ $overtime->interval }} Jam</td>
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
