@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Permissions</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Permissions</li>
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
                        <!-- left column -->
                        <div class="col-md-4">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Permissions</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="{{ route('permissions.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama Permissions</label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter Permissions" name="name">
                                            @error('nama')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                    <div class="col-8">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">No</th>
                                            <th>Nama Lengkap</th>
                                            <th >Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td class="project-actions text-right">
                                                    <a href="{{ url('master-users/permissions/'.$permission->uuid.'/edit') }}"
                                                        class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a data-toggle="modal" data-target="#modal-hapus{{ $permission->uuid }}" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modal-hapus{{ $permission->uuid }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-default">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Konfirmasi Hapus data</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah kamu yakin ingin menghapus data user
                                                                <b>{{ $permission->name }}</b> ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <form action="{{ url('master-users/permissions/'.$permission->uuid.'/delete') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
<<<<<<< HEAD
                                                                <button type="button" class="btn btn-outline-light"
                                                                    data-dismiss="modal" style="margin-left: -300px">Batal</button>
                                                                <button type="submit" class="btn btn-outline-light">Ya,
=======
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal" style="margin-left: -300px">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Ya,
>>>>>>> 87587bc79aa335c181124c666bab6b8967f3a21b
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
