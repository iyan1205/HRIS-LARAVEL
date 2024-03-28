@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Karyawan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('karyawan') }}">Karyawan</a></li>
                        <li class="breadcrumb-item active">Edit Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#karyawan" data-toggle="tab">Karyawan</a></li>
                                <li class="nav-item"><a class="nav-link" href="#pendidikan" data-toggle="tab">Pendidikan</a></li>
                                <li class="nav-item"><a class="nav-link" href="#kontak" data-toggle="tab">Kontak</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Pane Karyawan -->
                                <div class="tab-pane active" id="karyawan">
                                    <form action="{{ route('karyawan.update', ['id' => $karyawan->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="name" placeholder="Nama" name="name" value="{{ old('name', $karyawan->name) }}">
                                                @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="name" placeholder="NIK" name="nik" value="{{ old('nik', $karyawan->nik) }}">
                                                @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                                            <div class="col-sm-3">
                                                <select class="form-control select2bs4" id="jabatans" name="jabatan_id" style="width: 100%;">
                                                    @foreach ($jabatans as $id => $name)
                                                    <option value="{{ $id }}" {{ $karyawan->jabatan_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('jabatans')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="departemen" class="col-sm-3 col-form-label">Departemen</label>
                                            <div class="col-sm-3">
                                                <select class="form-control select2bs4" id="departemens" name="departemen_id" style="width: 100%;">
                                                    @foreach ($departemens as $id => $name)
                                                    <option value="{{ $id }}" {{ $karyawan->departemen_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('departemens')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="unit" class="col-sm-3 col-form-label">Unit</label>
                                            <div class="col-sm-3">
                                                <select class="form-control select2bs4" id="Unit" name="unit_id" style="width: 100%;">
                                                    @foreach ($units as $id => $name)
                                                    <option value="{{ $id }}" {{ $karyawan->unit_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>

                                                    @endforeach
                                                </select>
                                                @error('units')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                                @enderror
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tgl_kontrak1" class="col-sm-3 col-form-label">Tanggal Masuk Dinas 1</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="tgl_kontrak1" name="tgl_kontrak1" value="{{ old('tgl_kontrak1', $karyawan->tgl_kontrak1) }}">
                                                @error('tgl_kontrak1')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="akhir_kontrak1" class="col-sm-3 col-form-label">Tanggal Berakhir Kontrak ke 1</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="akhir_kontrak1" name="akhir_kontrak1" value="{{ old('akhir_kontrak1', $karyawan->akhir_kontrak1) }}">
                                                @error('akhir_kontrak1')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tgl_kontrak2" class="col-sm-3 col-form-label">Tanggal Masuk Dinas 2</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="tgl_kontrak2" name="tgl_kontrak2" value="{{ old('tgl_kontrak2', $karyawan->tgl_kontrak2) }}">
                                                @error('tgl_kontrak1')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="akhir_kontrak2" class="col-sm-3 col-form-label">Tanggal Berakhir Kontrak ke 2</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="akhir_kontrak2" name="akhir_kontrak2" value="{{ old('akhir_kontrak2', $karyawan->akhir_kontrak2) }}">
                                                @error('akhir_kontrak2')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label">Status Karyawan</label>
                                            <div class="col-sm-3">
                                                <select class="form-control" name="status" id="resignSelect">
                                                    <option value="active" {{ $karyawan->status == 'active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="resign" {{ $karyawan->status == 'resign' ? 'selected' : '' }}>Resign
                                                    </option>
                                                </select>
                                                @error('units')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div id="resignForm" style="display: {{ $karyawan->status == 'resign' ? 'block' : 'none' }};">
                                            <div class="form-group row">
                                                <label for="resign" class="col-sm-3 col-form-label">Alasan
                                                    Resign:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="resign_id" id="reason">
                                                        <option selected disabled>Pilih Alasan Resign</option>
                                                        @foreach ($resignReasons as $id => $name)
                                                        <option value="{{ $id }}" {{ $karyawan->resign_id == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgl_resign" class="col-sm-3 col-form-label">Tanggal
                                                    resign:</label>
                                                <div class="col-sm-3">
                                                    <input type="date" class="form-control" name="tgl_resign" value="{{ $karyawan->tgl_resign }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Other fields for Karyawan -->
                                        <div class="form-group row">
                                            <div class="offset-sm-0 col-sm-3">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div> <!-- Tab Pane Karyawan -->

                                <!-- Tab Pane Pendidikan -->
                                <div class="tab-pane" id="pendidikan">
                                    <form action="{{ route('karyawan.update', ['id' => $karyawan->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label for="institusi" class="col-sm-3 col-form-label">Asal Institusi</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="institusi" placeholder="Asal institusi" name="institusi" value="{{ $karyawan->pendidikan->institusi ?? '' }}">
                                                @error('institusi')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pendidikan" class="col-sm-3 col-form-label">Pendidikan
                                                Terakhir</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="inputPendidikan" placeholder="Pendidikan Terakhir" name="pendidikan_terakhir" value="{{ $karyawan->pendidikan->pendidikan_terakhir ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tahun_lulus" class="col-sm-3 col-form-label">Tahun
                                                Lulus</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="TahunLulus" placeholder="Tahun Lulus" name="tahun_lulus" value="{{ $karyawan->pendidikan->tahun_lulus }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nomer_ijazah" class="col-sm-3 col-form-label">Nomer
                                                Ijazah</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="nomer_ijazah" placeholder="Nomer Ijazah" name="nomer_ijazah" value="{{ $karyawan->pendidikan->nomer_ijazah }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nomer_str" class="col-sm-3 col-form-label">Nomer STR</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="nomer_str" placeholder="Nomer STR" name="nomer_str" value="{{ $karyawan->pendidikan->nomer_str }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exp_str" class="col-sm-3 col-form-label">Masa Aktif
                                                STR</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="exp_str" placeholder="Masa Aktif STR" name="exp_str" value="{{ $karyawan->pendidikan->exp_str }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="profesi" class="col-sm-3 col-form-label">Profesi</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="profesi" placeholder="Profesi" name="profesi" value="{{ $karyawan->pendidikan->profesi }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cert_profesi" class="col-sm-3 col-form-label">Sertifikat
                                                Profesi</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="cert_profesi" placeholder="Sertifikat Profesi" name="cert_profesi" value="{{ $karyawan->pendidikan->cert_profesi }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pelatihan" class="col-sm-3 col-form-label">Pelatihan</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="pelatihan" placeholder="Pelatihan Yang Di Ikuti" name="pelatihan" value="{{ $karyawan->pendidikan->pelatihan }}">
                                            </div>
                                        </div>

                                        <!-- Other fields for Pendidikan -->
                                        <div class="form-group row">
                                            <div class="offset-sm-0 col-sm-3">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div><!-- Tab Pane Pendidikan -->

                                <!-- Tab Pane Kontak -->
                                <div class="tab-pane" id="kontak">
                                    <form action="{{ route('karyawan.update', ['id' => $karyawan->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label for="nomer_ktp" class="col-sm-3 col-form-label">Nomer KTP</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" id="nomer_ktp" placeholder="Nomer KTP" name="nomer_ktp" value="{{ $karyawan->nomer_ktp }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="inputTempatlahir" placeholder="Tempat Lahir" name="tempat_lahir" value="{{ $karyawan->tempat_lahir }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tanggal_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" id="inputTanggallahir" placeholder="Tanggal Lahir" name="tanggal_lahir" value="{{ $karyawan->tanggal_lahir }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="alamat_ktp" class="col-sm-3 col-form-label">Alamat KTP</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="alamat_ktp" placeholder="Alamat KTP" name="alamat_ktp" value="{{ $karyawan->alamat_ktp }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                            <div class="form-check" style="margin-left: 10px;">
                                                <input class="form-check-input" type="radio" id="L" name="gender" value="L" {{ $karyawan->gender === 'L' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="l">L</label>
                                                <input class="form-check-input" type="radio" id="P" name="gender" value="P" {{ $karyawan->gender == 'P' ? 'checked' : '' }} style="margin-left: 6px;" required>
                                                <label class="form-check-label" for="p" style="margin-left: 24px;">P</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="status_perkawinan" class="col-sm-3 col-form-label">Status Perkawinan</label>
                                            <div class="form-check" style="margin-left: 10px;">
                                                <input class="form-check-input" type="radio" id="menikah" name="status_ktp" value="{{ $karyawan->status_ktp }}" required>
                                                <label class="form-check-label" for="menikah">Menikah</label>
                                                <input class="form-check-input" type="radio" id="belum_menikah" name="status_ktp" value="{{ $karyawan->status_ktp }}" style="margin-left: 6px;" required>
                                                <label class="form-check-label" for="belum_menikah" style="margin-left: 24px;">Belum Menikah</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="telepon" class="col-sm-3 col-form-label">Nomer Telepon</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" id="telepon" placeholder="Nomer Telepon" name="telepon" value="{{ $karyawan->telepon }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="{{ $karyawan->user->email }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="npwp" placeholder="NPWP" name="npwp" value="{{ $karyawan->npwp }}">
                                            </div>
                                        </div>

                                        <!-- Other Fields for Kontak -->
                                        <div class="form-group row">
                                            <div class="offset-sm-0 col-sm-3">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div> <!-- Tab Pane Kontak -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    document.getElementById('resignSelect').addEventListener('change', function() {
        var resignForm = document.getElementById('resignForm');
        if (this.value === 'resign') {
            resignForm.style.display = 'block';
        } else {
            resignForm.style.display = 'none';
        }
    });
</script>
@endsection