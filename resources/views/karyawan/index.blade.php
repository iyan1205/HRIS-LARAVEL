@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Karyawan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Karyawan</li>
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
                            <a href="{{ route('karyawan.create') }}" class="btn btn-primary mb-3">Tambah
                                Data</a>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <div class="input-group-append">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="karyawanTable">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIK</th>
                                        <th>Jabatan</th>
                                        <th>Departemen</th>
                                        <th>Unit</th>
                                        <th style="width: 150px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawans as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->jabatan->name }}</td>
                                        <td>{{ $item->departemen->name }}</td>
                                        <td>{{ $item->unit->name }}</td>
                                        <td class="project-actions text-right">
                                            <a href="{{ route('karyawan.edit', ['id' => $item->id]) }}" class="btn btn-success btn-sm"><i class="fas fa-pencil-alt"></i>
                                                Edit</a>
                                            <a data-toggle="modal" data-target="#modal-hapus{{ $item->id }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                Hapus</a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-hapus{{ $item->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-danger">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Konfirmasi Hapus data</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah kamu yakin ingin menghapus data user
                                                        <b>{{ $item->name }}</b> ?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <form action="{{ route('user.delete', ['id' => $item->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-outline-light">Ya,
                                                            Hapus
                                                        </button>
                                                    </form>
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