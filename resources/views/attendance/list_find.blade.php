@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Find Absensi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Back</a></li>
                            <li class="breadcrumb-item active">Find Absensi</li>
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
                                @if(isset($attendance) && !$attendance->isEmpty())
                                        <table class="table table-bordered table-hover"  id="karyawanTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>NIK</th>
                                                    <th>Jabatan</th>
                                                    <th>Departemen</th>
                                                    <th>Unit</th>
                                                    <th>Tanggal dan Jam Masuk</th>
                                                    <th>Foto Masuk</th>
                                                    <th>Tanggal dan Jam Keluar</th>
                                                    <th>Foto Keluar</th>
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
                                                    <td>
                                                        <img src="{{ asset('storage/' . $record->foto_jam_masuk) }}" alt="Foto Masuk" width="50" height="50">
                                                    </td>
                                                    <td>{{ $record->updated_at == $record->created_at ? ' ' : $record->updated_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>
                                                        @if ($record->foto_jam_keluar)
                                                            <img src="{{ asset('storage/' . $record->foto_jam_keluar) }}" alt="Foto Keluar" width="50" height="50">
                                                        @else
                                                            Tidak ada foto
                                                        @endif
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
                                    </div>
                                
                                @else
                                <div class="alert alert-info mt-3">
                                    Tidak ada data absensi pada rentang tanggal yang dipilih.
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
