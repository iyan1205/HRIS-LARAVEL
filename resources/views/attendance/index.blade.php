@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Kehadiran</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Kehadiran</li>
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
                            @if (!$attendance || $attendance->status != 'check_in')
                                <a href="{{ route('attendance.create') }}" class="btn btn-primary mb-3">Buat Kehadiran</a>
                            @endif
                            <a href="{{ route('attendace.records') }}" class="btn btn-warning mb-3">Riwayat Kehadiran</a>
                            </div>
<!-- /.card-header -->
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal</th>
                                            <th>Jam Check-In</th>
                                            <th>Jam Check-Out</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($attendance)
                                        <tr>
                                            <td>{{ $attendance->user->name }}</td>
                                            <td>{{ $attendance->date }}</td>
                                            <td>{{ $attendance->time_in }}</td>
                                            <td>{{ $attendance->time_out }}</td>
                                            <td>
                                            @if($attendance->status === 'check_in')
                                                <form action="{{ route('attendance.checkOut', $attendance->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="time_out_photo">Upload Foto</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="time_out_photo" name="time_out_photo" accept="image/*"  capture="camera" required>
                                                                <label class="custom-file-label" for="time_out_photo">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mb-3">Check Out</button>
                                                </form>
                                            @endif</td>
                                        </tr>
                                        @endif
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
