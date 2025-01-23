@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Riwayat Lembur: <b>{{ $user->karyawan->name ?? 'Admin'}}</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('overtime') }}">Lembur</a></li>
                            <li class="breadcrumb-item active">Riwayat Lembur</li>
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
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Jam</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overtimes as $overtime)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"><b>OT-{{ $overtime->id }}</b></span></td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->start_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->end_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $overtime->interval }}</td>
                                                <td>{{ $overtime->keterangan }}</td>
                                                <td>
                                                    @if($overtime->status == 'rejected')
                                                        <span class="badge bg-danger">
                                                            <a href="" title="Alasan Reject" data-toggle="modal" data-target="#modal-lg{{ $overtime->id }}">{{ $overtime->status }}</a>
                                                        </span>
                                                    @elseif($overtime->status == 'approved')
                                                        <span class="badge bg-success">{{ $overtime->status }}</span>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                            <div class="modal fade" id="modal-lg{{ $overtime->id }}">
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
                                                                    <input type="text" class="form-control" value="{{ $overtime->updated_by }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Updated at</label>
                                                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($overtime->updated_at)->format('d/m/Y H:i:s') }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="alasan_reject">Alasan Reject</label>
                                                                    <textarea class="form-control" id="alasan_reject{{ $overtime->id }}" name="alasan_reject" rows="3" disabled> {{ $overtime->alasan_reject }}</textarea>
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
