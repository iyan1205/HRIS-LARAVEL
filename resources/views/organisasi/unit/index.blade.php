@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Unit</h1>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Organisasi</a></li>
                        <li class="breadcrumb-item active">Unit</li>
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
                            <a href="{{ route('unit.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Units</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $unit->name }}</td>
                                        <td class="project-actions text-right">
                                            <a href="{{ route('unit.edit', ['id' => $unit->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a data-toggle="modal" data-target="#modal-hapus{{ $unit->id }}" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-hapus{{ $unit->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-default">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Konfirmasi Hapus data</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah kamu yakin ingin menghapus Unit
                                                        <b>{{ $unit->name }}</b> ?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <form action="{{ route('unit.delete', ['id' => $unit->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: -300px">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus
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