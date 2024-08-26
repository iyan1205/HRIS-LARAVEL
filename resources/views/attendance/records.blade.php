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
                            <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Kehadiran</a></li>
                            <li class="breadcrumb-item active">Riwayat Kehadiran</li>
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
                            <div class="card-body ">
                                <table class="table table-bordered table-hover" id="allTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal</th>
                                            <th>Jam Check-In</th>
                                            <th>Jam Check-Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendance as $index => $attendance)
                                        <tr>
                                            <td>{{ $attendance->user->name }}</td>
                                            <td>{{ $attendance->date }}</td>
                                            <td>{{ $attendance->time_in }}</td>
                                            <td>{{ $attendance->time_out }}</td>
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
