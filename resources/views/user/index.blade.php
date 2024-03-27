@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Users</li>
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
                                <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah Users</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if (!empty($user->getRoleNames()))
                                                        @foreach ($user->getRoleNames() as $rolename)
                                                            <span class="badge badge-primary">{{ $rolename }}</span>
                                                        @endforeach
                                                    @endif
                                                <td class="project-actions text-right">
                                                    <a href="{{ route('user.edit', ['id' => $user->id]) }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-pencil-alt"></i>
                                                        Edit</a>
                                                    <a data-toggle="modal" data-target="#modal-hapus{{ $user->id }}"
                                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                        Hapus</a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modal-hapus{{ $user->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-danger">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Konfirmasi Hapus data</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah kamu yakin ingin menghapus data user
                                                                <b>{{ $user->name }}</b> ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <form action="{{ route('user.delete', ['id' => $user->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-outline-light"
                                                                    data-dismiss="modal" style="margin-left: -300px">Batal</button>
                                                                <button type="submit" class="btn btn-outline-light">Ya, Hapus
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
