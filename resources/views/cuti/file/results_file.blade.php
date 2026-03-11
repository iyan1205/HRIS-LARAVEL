@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">File Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('file.cuti') }}">Home</a></li>
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
                               @if ($results->count() > 0)
                                    <form action="{{ route('file.cuti.downloadAll') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                                        <button type="submit" class="btn btn-success mb-3">
                                            <i class="fas fa-download"></i> Download All
                                        </button>
                                    </form>
                                @endif

                            </div>
                           
                            <div class="card-body ">
                                @if ($results->isEmpty())
                                    <p class="text-center">Tidak ada data cuti ditemukan.</p>
                                @else
                                    <table class="table table-bordered table-striped" id="karyawanTable">
                                        <thead>
                                            <tr>
                                                <th>Nama Karyawan</th>
                                                <th>Jabatan</th>
                                                <th>Jenis Cuti</th>
                                                <th>Kategori</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Akhir</th>
                                                <th>File Cuti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $row)
                                                <tr>
                                                    <td>{{ $row->karyawan_name }}</td>
                                                    <td>{{ $row->nama_jabatan }}</td>
                                                    <td>{{ $row->leave_type }}</td>
                                                    <td>{{ $row->kategori }}</td>
                                                    <td>{{ $row->created_at }}</td>
                                                    <td>{{ $row->start_date }}</td>
                                                    <td>{{ $row->end_date }}</td>
                                                    <td>
                                                        @if ($row->file_upload)
                                                            <a href="{{ route('file.cuti.download', $row->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-file-download"></i> Download
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Tidak ada file</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
