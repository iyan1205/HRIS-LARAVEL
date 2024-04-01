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
                                            <th>Jenis/Kategori</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveApplication as $cuti)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cuti->user->karyawan->name }}</td>
                                                <td>{{ $cuti->leavetype->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                                                </td>
                                                <td><span class="tag tag-success">{{ $cuti->status }}</span></td>
                                                <td class="project-actions text-right">
                                                    @can('approve cuti')
                                                        <form id="approveForm{{ $cuti->id }}" action="{{ route('leave-application.approve', $cuti->id) }}" method="post" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm rejectBtn" data-cuti-id="{{ $cuti->id }}"><i class="fas fa-times"></i> Reject</button>
                                                    <form id="rejectForm{{ $cuti->id }}" action="{{ route('leave-application.reject', $cuti->id) }}" method="post" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</button>
                                                    </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                            
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
