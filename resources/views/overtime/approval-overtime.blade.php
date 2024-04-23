@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Lembur</h1>
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
                            </div>
                            {{-- <div class="form-group">
                                <label>Date range:</label>
              
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="far fa-calendar-alt"></i>
                                    </span>
                                  </div>
                                  <input type="text" class="form-control float-right" id="reservation">
                                </div>
                                <!-- /.input group -->
                              </div> --}}
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Selang Waktu</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overtimes as $overtime)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $overtime->user->karyawan->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->start_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($overtime->end_date)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $overtime->interval }}</td>
                                                <td class="project-actions text-right">
                                                    @can('approve cuti')
                                                        <button type="button" class="btn btn-success btn-sm approveBtn" data-overtime-id="{{ $overtime->id }}" data-toggle="modal" data-target="#modal-ap{{ $overtime->id }}"><i class="fas fa-check"></i> Approve</button>
                                                    @endcan
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modal-ap{{ $overtime->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Approve Pengajuan Lembur</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form id="approveForm{{ $overtime->id }}" action="{{ route('overtime.approve', $overtime->id) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <p>Apakah Yakin Pengajuan overtime <b> {{ $overtime->user->karyawan->name }} </b> akan di Approve  ?</p>
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
    <script>
        // Script untuk menangani pengiriman formulir ketika tombol "Ya, Approve" diklik
        $(document).on('click', '.approveBtn', function () {
            var overtimeId = $(this).data('cuti-id');
            $('#approveForm' + overtimeId).submit();
        });
    </script>
@endsection
