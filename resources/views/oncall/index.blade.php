@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pengajuan On Call</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">On Call</li>
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
                                <a href="{{ route('oncall.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                                <a href="{{ route('oncall.riwayat') }}" class="btn btn-warning mb-3">Riwayat Pengajuan On-Call</a>
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
                                        @foreach ($oncalls as $oncall)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: center;"> <span class="badge bg-info"><b>OC-{{ $oncall->id }}</b></span></td>
                                                <td>{{ $oncall->user->karyawan->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($oncall->start_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($oncall->end_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $oncall->interval }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $oncall->status }} 
                                                    </span></td>
                                                <td>
                                                <a data-toggle="modal" data-target="#modal-detail{{  $oncall->id }}" class="btn btn-info btn-sm" title="Keterangan"><i class="fas fa-eye"></i></a>
                                                @if (is_null($oncall->updated_by))
                                                    <a href="{{ route('oncall.edit', ['id' => $oncall->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                                @endif
                                                </td>
                                            </tr>
                                            
                                            <div class="modal fade" id="modal-detail{{ $oncall->id }}">
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
                                                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan ..." readonly >{{ $oncall->keterangan }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Diperbarui pada:</label>
                                                                <input type="text" class="form-control" value="{{ $oncall->updated_at == $oncall->created_at ? 'Belum Diperbarui' : \Carbon\Carbon::parse($oncall->updated_at)->format('d/m/Y H:i:s') }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Diperbarui oleh:</label>
                                                                <input type="text" class="form-control" value="{{ $oncall->updated_by ?? 'Belum Diperbarui Atasan'}}" readonly> 
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
