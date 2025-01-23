@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Saldo Cuti</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Saldo Cuti</li>
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
                                {{-- <a href="{{ route('saldo-cuti.create') }}" class="btn btn-primary mb-3">Tambah Data</a> --}}
                            </div>
                            
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Sisa Saldo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveBalance as $saldocuti)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $saldocuti->user->name }}</td>
                                                <td>{{ $saldocuti->saldo_cuti }}</td>
                                                <td class="project-actions text-right">
                                                    <a href="{{ route('saldo-cuti.edit', ['id' => $saldocuti->id]) }}" class="btn btn-success btn-sm"><i class="fas fa-pencil-alt"></i>
                                                        Edit</a>
                                                    {{-- <a data-toggle="modal" data-target="#modal-hapus{{ $saldocuti->id }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                        Hapus</a> --}}
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
    
@endsection
