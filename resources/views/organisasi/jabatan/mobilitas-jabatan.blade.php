@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mobilitas Jabatan</h1>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Organisasi</a></li>
                        <li class="breadcrumb-item active">Mobilitas Jabatan</li>
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
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <table class="table table-bordered table-hover" id="allTable">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Aspek</th>
                                        <th>Jabatan Sebelumnya</th>
                                        <th>Jabatan Baru</th>
                                        <th>Departemen Sebelumnya</th>
                                        <th>Departemen Baru</th>
                                        <th>Unit Sebelumnya</th>
                                        <th>Unit Baru</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobilitasData as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->karyawan->name }}</td>
                                        <td>{{ $data->aspek }}</td>
                                        <td>{{ $data->jabatan_sekarang }}</td>
                                        <td>{{ $data->jabatan_baru }}</td>
                                        <td>{{ $data->departemen_sekarang }}</td>
                                        <td>{{ $data->departemen_baru }}</td>
                                        <td>{{ $data->unit_sekarang }}</td>
                                        <td>{{ $data->unit_baru }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('m/d/Y', $data->tanggal_efektif)->format('d/m/Y') }}</td>
                                        <td class="project-actions text-right">
                                            <a href="{{ route('mobilitas.edit', ['id' => $data->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
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