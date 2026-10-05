@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Benefit Kartap</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('benefit-kartap.laporan') }}">Back</a></li>
                            <li class="breadcrumb-item active">Laporan Benefit Kartap</li>
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
                                @if(isset($benefitKartaps) && !$benefitKartaps->isEmpty())
                               
                                        <table class="table table-bordered table-hover" id="lap_benefit_kartap">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIK</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Jabatan</th>
                                                    <th>Departemen</th>
                                                    <th>Instalasi/Divisi</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Jenis Benefit & Flapon</th>
                                                    <th>Total Nominal Klaim</th>
                                                    <th>Tanggal Approve SPV SDM</th>
                                                    <th>Tanggal Approve Manajer SDM</th>
                                                    <th>Tanggal Approve Manajer Keuangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($benefitKartaps as $record)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $record->user->karyawan->nik }}</td>
                                                    <td>{{ $record->user->karyawan->name }}</td>
                                                    <td>{{ $record->user->karyawan->jabatan->name }}</td>
                                                    <td>{{ $record->user->karyawan->unit->name }}</td>
                                                    <td>{{ $record->user->karyawan->departemen->name }}</td>
                                                    <td>{{ $record->created_at->format('d/m/Y') }} {{ \Carbon\Carbon::parse($record->created_at)->format('H:i:s') }}</td>
                                                    <td>{{$record->jenis_benefit}}</td>
                                                    <td> {{ number_format($record->nominal, 0, ',', '.') }}</td>
                                                    <td>{{ $record->approval_1_at ? $record->approval_1_at->format('d/m/Y H:i:s') : 'Belum disetujui' }}</td>
                                                    <td>{{ $record->approval_2_at ? $record->approval_2_at->format('d/m/Y H:i:s') : 'Belum disetujui' }}</td>
                                                    <td>{{ $record->approved_at ? $record->approved_at->format('d/m/Y H:i:s') : 'Belum disetujui' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                
                                @else
                                <div class="alert alert-info mt-3">
                                    Tidak ada data pada rentang tanggal yang dipilih.
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
