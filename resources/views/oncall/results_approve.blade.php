@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Approve Oncall</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('approval-oncall') }}">Approval On Call</a></li>
                            <li class="breadcrumb-item active">Cancel Oncall</li>
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
                                            <th>Tanggal Awal</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Total Jam</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $oncall)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $oncall->karyawan_name }}</td>
                                            <td>{{ $oncall->nama_jabatan }}</td>
                                            <td>{{ $oncall->start_date }}</td>
                                            <td>{{ $oncall->end_date }}</td>
                                            <td>{{ $oncall->interval }}</td>
                                            <td>{{ $oncall->keterangan }}</td>
                                            <td class="project-actions text-right">
                                                @can('cancel_approve')
                                                <button type="button" class="btn btn-danger btn-sm rejectBtn" data-oncall-id="{{ $oncall->id }}" data-toggle="modal" data-target="#modal-reject{{ $oncall->id }}"><i class="fas fa-times"></i> Cancel</button>
                                                @endcan
                                            </td>
                                        </tr>
                                        <!-- Modal Reject -->
                                        <div class="modal fade" id="modal-reject{{ $oncall->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Reject Pengajuan On Call</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="rejectForm{{ $oncall->id }}" action="{{ route('oncall.reject', $oncall->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="alasan_reject{{ $oncall->id }}">Alasan Reject:</label>
                                                                <textarea class="form-control" id="alasan_reject{{ $oncall->id }}" name="alasan_reject" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Ya, Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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
                            <a href="{{ route('approval-oncall') }}" class=" btn btn-secondary">Kembali</a>
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
