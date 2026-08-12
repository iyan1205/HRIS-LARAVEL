@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Benefit Kartap</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Benefit Kartap</li>
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

                        {{-- ============ CARD 1: TABEL RIWAYAT PENGAJUAN ============ --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Pengajuan</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="allTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Karyawan</th>
                                                <th>Jenis Benefit</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Nominal</th>
                                                <th>Form Pengajuan</th>
                                                <th>Resume</th>
                                                <th>Bukti Pembayaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($benefitKartaps as $benefitKartap)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $benefitKartap->user->karyawan->name }}</td>
                                                    <td>{{ strtoupper($benefitKartap->jenis_benefit) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($benefitKartap->created_at)->format('d/m/Y') }}</td>
                                                    <td class="text-right">Rp {{ number_format($benefitKartap->nominal, 0, ',', '.') }}</td>
                                                    <td><a href="{{ asset('storage/' . $benefitKartap->form_pengajuan) }}" target="_blank">Lihat Form</a></td>
                                                    <td><a href="{{ asset('storage/' . $benefitKartap->resume) }}" target="_blank">Lihat Resume</a></td>
                                                    <td><a href="{{ asset('storage/' . $benefitKartap->bukti_pembayaran) }}" target="_blank">Lihat Bukti</a></td>
                                                    <td>
                                                        @if ($benefitKartap->status === 'pending')
                                                            <span class="badge bg-warning">{{ $benefitKartap->status }}</span>
                                                        @elseif ($benefitKartap->status === 'rejected')
                                                            <span class="badge bg-danger">{{ $benefitKartap->status }}</span>
                                                        @else
                                                            <span class="badge bg-success">{{ $benefitKartap->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <a data-toggle="modal" data-target="#modal-detail{{ $benefitKartap->id }}" class="btn btn-info btn-sm" title="Keterangan">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if ($benefitKartap->status === 'pending' || $benefitKartap->status === 'rejected')
                                                            <a href="{{ route('benefit-kartap.edit', $benefitKartap->id) }}" class="btn btn-success btn-sm" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="modal-detail{{ $benefitKartap->id }}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content bg-default">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Detail Persetujuan</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($benefitKartap->status === 'rejected')
                                                                    <div class="alert alert-danger" role="alert">
                                                                        <strong>Alasan Penolakan:</strong> {{ $benefitKartap->alasan_reject }}
                                                                    </div>
                                                                @endif

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ $benefitKartap->approval1By->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" value="{{ $benefitKartap->approval1By->karyawan->name ?? 'Belum Disetujui SPV SDM' }}" readonly>
                                                                            @if($benefitKartap->approval_1_at)
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-success text-white"><i class="fas fa-check"></i></span>
                                                                                </div>
                                                                            @elseif($benefitKartap->status === 'rejected')
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-danger text-white"><i class="fas fa-times"></i></span>
                                                                                </div>
                                                                            @else
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-warning text-white"><i class="fas fa-clock"></i></span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Diperbarui pada:</label>
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approval_1_at ? \Carbon\Carbon::parse($benefitKartap->approval_1_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ $benefitKartap->approval2By->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" value="{{ $benefitKartap->approval2By->karyawan->name ?? 'Belum Disetujui Manager SDM' }}" readonly>
                                                                            @if($benefitKartap->approval_2_at)
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-success text-white"><i class="fas fa-check"></i></span>
                                                                                </div>
                                                                            @else
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-warning text-white"><i class="fas fa-clock"></i></span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Diperbarui pada:</label>
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approval_2_at ? \Carbon\Carbon::parse($benefitKartap->approval_2_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>{{ $benefitKartap->approvedBy->karyawan->jabatan->name ?? 'Jabatan tidak ditemukan' }}</label>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" value="{{ $benefitKartap->approvedBy->karyawan->name ?? 'Belum Disetujui Atasan' }}" readonly>
                                                                            @if($benefitKartap->approved_at)
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-success text-white"><i class="fas fa-check"></i></span>
                                                                                </div>
                                                                            @else
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text bg-warning text-white"><i class="fas fa-clock"></i></span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Diperbarui pada:</label>
                                                                        <input type="text" class="form-control" value="{{ $benefitKartap->approved_at ? \Carbon\Carbon::parse($benefitKartap->approved_at)->format('d/m/Y H:i:s') : 'Belum Disetujui' }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted py-3">Belum ada pengajuan klaim</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        
                        {{-- ============ CARD 2: SISA PLAFON BENEFIT (SEMUA JENIS) ============ --}}
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="card-title mb-2 mb-sm-0">
                                    <i class="fas fa-wallet text-primary mr-1"></i>
                                    Sisa Plafon Benefit Anda
                                </h3>

                                <form method="GET" action="{{ route('benefit-kartap.index') }}" class="d-flex align-items-center">
                                    <label for="filterTahun" class="mr-2 mb-0 text-muted font-weight-normal">
                                        <i class="far fa-calendar-alt mr-1"></i> Periode
                                    </label>
                                    <select
                                        name="tahun"
                                        id="filterTahun"
                                        class="form-control custom-select form-control-sm"
                                        style="min-width: 110px; font-weight: 600; cursor: pointer;"
                                        onchange="this.form.submit()"
                                    >
                                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                                Tahun {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </form>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Jenis Benefit</th>
                                                <th>Jumlah Klaim</th>
                                                <th class="text-right">Total Terpakai</th>
                                                <th class="text-right">Plafon</th>
                                                <th class="text-right">Sisa Plafon</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center" style="width: 130px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($rekapPlafon as $item)
                                            <tr class="{{ !$item['sudah_diajukan'] ? 'table-light' : '' }}">
                                                <td>
                                                    {{ $item['jenis_benefit'] }}
                                                    @if(!$item['sudah_diajukan'])
                                                        <span class="badge badge-secondary ml-1">Belum Diambil</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item['jumlah_klaim'] }}</td>
                                                <td class="text-right">Rp {{ number_format($item['total_nominal'], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ $item['plafon'] !== null ? 'Rp ' . number_format($item['plafon'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item['sisa_plafon'] !== null ? 'Rp ' . number_format($item['sisa_plafon'], 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if(!$item['sudah_diajukan'])
                                                        <span class="badge badge-secondary">Belum Diambil</span>
                                                    @elseif($item['melebihi_plafon'] === true)
                                                        <span class="badge badge-danger">Melebihi Plafon</span>
                                                    @elseif($item['melebihi_plafon'] === false)
                                                        <span class="badge badge-success">Aman</span>
                                                    @else
                                                        <span class="badge badge-light">Tidak ada plafon</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(!$item['sudah_diajukan'])
                                                        <a href="{{ route('benefit-kartap.create') }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-plus"></i> Ajukan
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">Tidak ada data benefit untuk level ini</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col-12 -->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection