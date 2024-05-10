@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Cuti</li>
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
                                <a href="{{ route('cuti.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                                <a href="{{ route('riwayat-cuti') }}" class="btn btn-warning mb-3">Riwayat Pengajuan Cuti</a>
                                
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
                                            <th>Kategori</th>
                                            <th>Jenis</th>
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
                                                <td>{{ $cuti->user->karyawan->name }}</td>
                                                <td>{{ $cuti->leavetype->kategori_cuti }}</td>
                                                <td>{{ $cuti->leavetype->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $cuti->total_days }} Hari</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <a href="" title="Alasan Reject" data-toggle="modal" data-target="#modal-lg{{ $cuti->id }}">{{ $cuti->status }}</a>
                                                    </span>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-lg{{ $cuti->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Riwayat Approve</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="">Approver</label>
                                                                    <input type="text" class="form-control" value="{{ $cuti->updated_by }}" readonly> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Approved at</label>
                                                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cuti->updated_at)->format('d/m/Y H:i:s') }}" readonly> 
                                                                </div>
                                                                <div>
                                                                    <label for="file_upload">Dokumen Pendukung</label>
                                                                    <a href="{{ asset('storage/'. $cuti->file_upload) }}" class="form-control" readonly target="_blank">Lihat Dokumen</a>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Mendaftarkan event click pada semua tombol rejectBtn
            const rejectButtons = document.querySelectorAll('.rejectBtn');
            rejectButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const cutiId = this.getAttribute('data-cuti-id');
    
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menolak pengajuan cuti ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Tolak',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika pengguna mengonfirmasi, kirim permintaan Ajax untuk menolak pengajuan cuti
                            document.getElementById('rejectForm' + cutiId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
