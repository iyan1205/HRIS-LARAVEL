@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pengajuan Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Lembur</li>
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
                                <a href="{{ route('overtime.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                                <a href="{{ route('overtime.riwayat') }}" class="btn btn-warning mb-3">Riwayat Pengajuan Lembur</a>
                            </div>
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pengajuan</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Jam</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overtimes as $overtime)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"><b>OT-{{ $overtime->id }}</b></span></td>
                                                <td>{{ $overtime->user->karyawan->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->start_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->end_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $overtime->interval }}</td>
                                                <td><span class="badge bg-secondary">{{ $overtime->status }}</span></td>
                                                <td>
                                                <a data-toggle="modal" data-target="#modal-detail{{  $overtime->id }}" class="btn btn-info btn-sm" title="Keterangan"><i class="fas fa-eye"></i></a>
                                                @if (is_null($overtime->updated_by))
                                                    <a href="{{ route('overtime.edit', ['id' => $overtime->id]) }}" class="btn btn-success btn-sm" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-detail{{ $overtime->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Detail</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="keterangan">Keterangan:</label>
                                                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan ..." readonly >{{ $overtime->keterangan }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Diperbarui pada:</label>
                                                                <input type="text" class="form-control" value="{{ $overtime->updated_at == $overtime->created_at ? 'Belum Diperbarui' : \Carbon\Carbon::parse($overtime->updated_at)->format('d/m/Y H:i:s') }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Diperbarui oleh:</label>
                                                                <input type="text" class="form-control" value="{{ $overtime->updated_by ?? 'Belum Diperbarui Atasan'}}" readonly> 
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
