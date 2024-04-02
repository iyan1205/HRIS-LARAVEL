@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Karyawan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('karyawan') }}">Karyawan</a></li>
                            <li class="breadcrumb-item active">Tambah Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('karyawan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- /.Karyawan -->
                        <div class="col-md-6">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Karyawan <span class="red-star">*</span>
                                        @error('nik')
                                        <span  class="red-star">
                                            <small>{{ $message }}</small>
                                        </span>
                                        @enderror
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                   
                                    <div class="form-group">
                                        <label for="name" class="form-label">Nama Karyawan</label>
                                        <input type="text" class="form-control" id="name" placeholder="Nama Lengkap"
                                            name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="user_id" class="form-label">User:</label>
                                        <select class="form-control select2bs4" id="user_id" name="user_id"
                                            style="width: 100%;" required>
                                            <option selected disabled>Pilih Users</option>
                                            @foreach ($users as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('user_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="number" class="form-control" id="nik" placeholder="NIK" minlength="4"
                                            name="nik" value="{{ old('nik') }}" required>
                                        @error('nik')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jabatan_id" class="form-label">Jabatan</label>
                                        <select class="form-control select2bs4" id="jabatan_id" name="jabatan_id"
                                            style="width: 100%;" required>
                                            <option selected value="">Pilih Jabatan
                                            </option>
                                            @foreach ($jabatans as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('jabatan_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jabatan_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="departemen" class="form-label">Departemen</label>
                                        <select class="form-control select2bs4" id="departemens" name="departemen_id"
                                            style="width: 100%;">
                                            <option selected value="" required>Pilih Departemen</option>
                                            @foreach ($departemens as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('departemen_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departemen_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="unit" class="form-label">Unit</label>
                                        <select class="form-control select2bs4" id="Unit" name="unit_id"
                                            style="width: 100%;" required>
                                            <option selected value="" required>Pilih Unit
                                            </option>
                                            @foreach ($units as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('unit_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status_karyawan">Status Karyawan</label>
                                        <select class="form-control" name="status_karyawan" id="">
                                            <option selected value="">Pilih Status Karyawan </option>
                                            <option value="kontrak" {{ old('status_karyawan') == 'kontrak' ? 'selected' : '' }}>
                                                Kontrak
                                            </option>
                                            <option value="kartap" {{ old('status_karyawan') == 'kartap' ? 'selected' : '' }}>
                                                Karyawan Tetap
                                            </option>
                                            <option value="fulltime" {{ old('status_karyawan') == 'fulltime' ? 'selected' : '' }}>
                                                Fulltime
                                            </option>
                                            <option value="parttime" {{ old('status_karyawan') == 'parttime' ? 'selected' : '' }}>
                                                Parttime
                                            </option>
                                        </select>
                                        @error('status_karyawan')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tgl_kontrak1" class="form-label">Tanggal Masuk Dinas</label>
                                        <input type="date" class="form-control" id="tgl_kontrak1" name="tgl_kontrak1" value="{{ old('tgl_kontrak1') }}"
                                            required>
                                        @error('tgl_kontrak1')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="akhir_kontrak1" class="form-label">Masa Kontrak</label>
                                        <input type="date" class="form-control" id="akhir_kontrak1" name="akhir_kontrak1"
                                            value="{{ old('akhir_kontrak1') }}" required>
                                        @error('akhir_kontrak1')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                </div> {{-- card-body --}}
                            </div> {{-- card-primary --}}
                        </div> {{-- col --}}
                        <!-- /.kontak -->
                        <div class="col-sm-6">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Kontak <span class="red-star">*</span> <br>
                                        @error('nomer_ktp','telepon','npwp','alamat_ktp','gender','status_ktp')
                                        <span  class="red-star">
                                                <small>{{ $message }}</small>
                                        </span>
                                        @enderror
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nomer_ktp" class="form-label">NIK
                                            KTP</label>
                                        <input type="number" class="form-control" id="nomer_ktp" placeholder="No KTP"
                                            name="nomer_ktp" value="{{ old('nomer_ktp') }}" required>
                                        @error('nomer_ktp')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="telepon" class="form-label">Nomer
                                            Telepon</label>
                                        <input type="number" class="form-control" id="telepon"
                                            placeholder="No Telepon" name="telepon" value="{{ old('telepon') }}"
                                            required>
                                        @error('telepon')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="npwp" class="form-label">Nomer
                                            NPWP</label>
                                        <input type="text" class="form-control" id="npwp" placeholder="No NPWP"
                                            name="npwp" value="{{ old('npwp') }}">
                                        @error('npwp')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" placeholder="Alamat"
                                            name="alamat_ktp" value="{{ old('alamat_ktp') }}" required>
                                        @error('alamat_ktp')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_kelamin" class="form-label">Jenis
                                            Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="L" name="gender"
                                                value="L"  {{ old('gender') == 'L' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="L">Laki -
                                                Laki</label>
                                            <input class="form-check-input" type="radio" id="P" name="gender"
                                                value="P" style="margin-left: 6px;" {{ old('gender') == 'P' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="P"
                                                style="margin-left: 24px;">Perempuan</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status_ktp" class="form-label">Status
                                            Perkawinan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="menikah"
                                                name="status_ktp" value="Menikah"
                                                {{ old('status_ktp') == 'Menikah' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="menikah">Menikah</label>
                                            <input class="form-check-input" type="radio" id="belum_menikah"
                                                name="status_ktp" value="Belum Menikah" style="margin-left: 6px;"
                                                {{ old('status_ktp') == 'Belum Menikah' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="belum_menikah"
                                                style="margin-left: 24px;">Belum
                                                Menikah</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tempat_lahir" class="form-label">Tempat
                                            Lahir</label>
                                        <input type="text" class="form-control" id="inputTempatlahir"
                                            placeholder="Tempat Lahir" name="tempat_lahir"
                                            value="{{ old('tempat_lahir') }}" required>
                                        @error('tempat_lahir')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_lahir" class="form-label">Tanggal
                                            Lahir</label>
                                        <input type="date" class="form-control" id="inputTanggallahir"
                                            name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                        @error('tanggal_lahir')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>

                                </div> {{-- card-body --}}
                            </div> {{-- card-info --}}
                        </div>
                        <!-- /.Pendidikan -->
                        <div class="col-md-6">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Pendidikan <span class="red-star">*</span></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="institusi" class="form-label">Asal
                                            Institusi</label>
                                        <input type="text" class="form-control" id="inputInstitusi"
                                            placeholder="Institusi" value="{{ old('institusi') }}" name="institusi"
                                            required>
                                        @error('institusi')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="pendidikan" class="form-label">Pendidikan
                                            Terakhir</label>
                                        <input type="text" class="form-control" id="inputPendidikan"
                                            placeholder="Pendidikan Terakhir" value="{{ old('pendidikan_terakhir') }}"
                                            name="pendidikan_terakhir" required>
                                        @error('pendidikan_terakhir')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tahun_lulus" class="form-label">Tahun
                                            Lulus</label>
                                        <input type="number" class="form-control" id="TahunLulus"
                                            placeholder="Tahun Lulus" value="{{ old('tahun_lulus') }}"
                                            name="tahun_lulus" required>
                                        @error('tahun_lulus')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="no ijazah" class="form-label">Nomer
                                            Ijazah</label>
                                        <input type="text" class="form-control" id="nomer_ijazah"
                                            placeholder="Nomer Ijazah" value="{{ old('nomer_ijazah') }}"
                                            name="nomer_ijazah">
                                    </div>

                                </div> {{-- card-body --}}
                            </div> {{-- card-primary --}}
                        </div>
                        <!-- /.Paramedis -->
                        <div class="col-md-6">
                            <div class="card card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Paramedis</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="no str" class="form-label">Nomer
                                            STR</label>
                                        <input type="text" class="form-control" id="nomer_str"
                                            value="{{ old('nomer_str') }}" placeholder="Nomer STR" name="nomer_str">

                                    </div>

                                    <div class="form-group">
                                        <label for="expired str" class="form-label">Masa
                                            Berlaku
                                            STR</label>
                                        <input type="date" class="form-control" id="exp_str"
                                            value="{{ old('exp_str') }}" name="exp_str">

                                    </div>

                                    <div class="form-group">
                                        <label for="profesi" class="form-label">Profesi</label>
                                        <input type="text" class="form-control" id="profesi" name="profesi"
                                            placeholder="Profesi" value="{{ old('profesi') }}">
                                        @error('profesi')
                                            <small>{{ $message }}</small>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label for="cert profesi" class="form-label">Sertifikat
                                            Profesi</label>
                                        <input type="text" class="form-control" id="cert_profesi" name="cert_profesi"
                                            placeholder="Sertifikat Profesi" value="{{ old('cert_profesi') }}">

                                        @error('cert_profesi')
                                            <small>{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div> {{-- card-body --}}
                            </div> {{-- card-primary --}}
                        </div> {{-- col --}}
                    </div> {{-- row --}}

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('karyawan') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </div>

                </form>
            </div> <!-- /.container-fluid -->
        </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->
@endsection
