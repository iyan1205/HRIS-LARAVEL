@extends('layout.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Absensi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('attendance.laporan') }}">Back</a>
                            </li>
                            <li class="breadcrumb-item active">Laporan Absensi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Form Filter -->
                                <form action="{{ route('attendance.find.report') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <!-- Input Tanggal Awal -->
                                            <div class="col-4">
                                                <label for="start_date">Tanggal Awal:</label>
                                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                                    <input 
                                                        type="text" 
                                                        class="form-control datetimepicker-input" 
                                                        data-target="#start_date" 
                                                        name="start_date" 
                                                        required 
                                                    />
                                                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Input Tanggal Akhir -->
                                            <div class="col-4">
                                                <label for="end_date">Tanggal Akhir:</label>
                                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                                    <input 
                                                        type="text" 
                                                        class="form-control datetimepicker-input" 
                                                        data-target="#end_date" 
                                                        name="end_date" 
                                                        required 
                                                    />
                                                    <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tombol Cari -->
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </form>

                                <!-- Hasil Pencarian -->
                                @if(isset($attendance))
                                    @if(!$attendance->isEmpty())
                                        <table class="table table-bordered table-hover mt-4" id="laporan2">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>NIK</th>
                                                    <th>Jabatan</th>
                                                    <th>Departemen</th>
                                                    <th>Unit</th>
                                                    <th>Tanggal dan Jam Masuk</th>
                                                    <th>Tanggal dan Jam Keluar</th>
                                                    <th>Total Jam</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendance as $record)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $record->user->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $record->user->karyawan->nik ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $record->user->karyawan->jabatan->name ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $record->user->karyawan->departemen->name ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $record->user->karyawan->unit->name ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $record->created_at->format('d/m/Y H:i:s') }}</td>
                                                        <td>{{ $record->updated_at == $record->created_at ? ' ' : $record->updated_at->format('d/m/Y H:i:s') }}</td>
                                                        <td>
                                                            @if ($record->total_duration === 'Tidak absen pulang')
                                                                <span class="badge badge-danger">{{ $record->total_duration }}</span>
                                                            @else
                                                                {{ $record->total_duration }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <!-- Tombol Download -->
                                        <form method="GET" action="{{ route('download_attendance_report') }}" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                                            <button type="submit" class="btn btn-success">
                                                Download Laporan
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-info mt-4 text-center">
                                            <strong>Informasi:</strong> Data tidak ditemukan untuk rentang tanggal yang dipilih.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
