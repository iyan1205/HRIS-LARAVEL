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
                            <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
                            <li class="breadcrumb-item active">Tambah Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#karyawan"
                                            data-toggle="tab">Karyawan</a>
                                    </li>
                                    {{-- <li class="nav-item"><a class="nav-link" href="#pendidikan"
                                            data-toggle="tab">Pendidikan</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Kontak</a>
                                    </li> --}}
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="karyawan">
                                        <form action="{{ route('karyawan.store') }}" method="POST" class="form-horizontal">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="nama" class="form-label">Nama
                                                        Lengkap:</label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Nama Lengkap" name="name"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="users" class="form-label">User:</label>
                                                    <select class="form-control select2bs4" id="users" name="user_id"
                                                        style="width: 100%;" required>
                                                        <option selected disabled required>Pilih Users</option>
                                                        @foreach ($users as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('users')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-1">
                                                    <label for="nik" class="form-label">NIK</label>
                                                    <input type="number" class="form-control" id="nik"
                                                        placeholder="NIK" name="nik" value="{{ old('nik') }}"
                                                        required>
                                                    @error('nik')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="nomer_ktp" class="form-label">Nomer KTP</label>
                                                    <input type="number" class="form-control" id="nomer_ktp"
                                                        placeholder="No KTP" name="nomer_ktp" required>
                                                    @error('nomer_ktp')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="telepon" class="form-label">Nomer Telepon</label>
                                                    <input type="number" class="form-control" id="telepon"
                                                        placeholder="No Telepon" name="telepon" required>
                                                    @error('telepon')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="npwp" class="form-label">Nomer NPWP</label>
                                                    <input type="text" class="form-control" id="npwp"
                                                        placeholder="No NPWP" name="npwp" required>
                                                    @error('npwp')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="jabatan" class="form-label">Jabatan</label>
                                                    <select class="form-control select2bs4" id="jabatans" name="jabatan_id"
                                                        style="width: 100%;" required>
                                                        <option selected disabled required>Pilih Jabatan</option>
                                                        @foreach ($jabatans as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('jabatans')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="departemen" class="form-label">Departemen</label>
                                                    <select class="form-control select2bs4" id="departemens"
                                                        name="departemen_id" style="width: 100%;">
                                                        <option selected disabled required>Pilih Departemen</option>
                                                        @foreach ($departemens as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('departemens')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="unit" class="form-label">Unit</label>
                                                    <select class="form-control select2bs4" id="Unit" name="unit_id"
                                                        style="width: 100%;" required>
                                                        <option selected disabled required>Pilih Unit</option>
                                                        @foreach ($units as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}
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
                                                <div class="col-sm-2">
                                                    <label for="tgl_kontrak1" class="form-label">Tanggal Masuk
                                                        Dinas</label>
                                                    <input type="date" class="form-control" id="tgl_kontrak1"
                                                        name="tgl_kontrak1" value="{{ old('tgl_kontrak1') }}" required>
                                                    @error('tgl_kontrak1')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="akhir_kontrak1" class="form-label">Masa Kontrak</label>
                                                    <input type="date" class="form-control" id="akhir_kontrak1"
                                                        name="akhir_kontrak1" value="{{ old('akhir_kontrak1') }}"
                                                        required>
                                                    @error('akhir_kontrak1')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label for="alamat" class="form-label">Alamat</label>
                                                    <input type="text" class="form-control" id="alamat"
                                                        placeholder="Alamat" name="alamat_ktp" required>
                                                    @error('alamat_ktp')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control" id="inputTempatlahir"
                                                        placeholder="Tempat Lahir" name="tempat_lahir" required>
                                                    @error('tempat_lahir')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" id="inputTanggallahir"
                                                        name="tanggal_lahir" required>
                                                    @error('tanggal_lahir')
                                                        <small>
                                                            <p class="text-danger">{{ $message }}</p>
                                                        </small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="l"
                                                            name="gender" value="l" required>
                                                        <label class="form-check-label" for="l">L</label>
                                                        <input class="form-check-input" type="radio" id="p"
                                                            name="gender" value="p" style="margin-left: 6px;"
                                                            required>
                                                        <label class="form-check-label" for="p"
                                                            style="margin-left: 24px;">P</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3" style="margin-left: -55px;">
                                                    <label for="status_ktp" class="form-label">Status Perkawinan</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="menikah"
                                                            name="status_ktp" value="menikah" required>
                                                        <label class="form-check-label" for="menikah">Menikah</label>
                                                        <input class="form-check-input" type="radio" id="belum_menikah"
                                                            name="status_ktp" value="belum menikah"
                                                            style="margin-left: 6px;" required>
                                                        <label class="form-check-label" for="belum_menikah"
                                                            style="margin-left: 24px;">Belum Menikah</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label for="institusi" class="form-label">Asal Institusi</label>
                                                    <input type="text" class="form-control" id="inputInstitusi"
                                                        placeholder="institusi" name="institusi" required>
                                                    @error('institusi')
                                                        <small>{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="pendidikan" class="form-label">Pendidikan Terakhir</label>
                                                    <input type="text" class="form-control" id="inputPendidikan"
                                                        placeholder="Pendidikan Terakhir" name="pendidikan_terakhir"
                                                        required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="tahun lulus" class="form-label">Tahun Lulus</label>
                                                    <input type="number" class="form-control" id="TahunLulus"
                                                        placeholder="Tahun Lulus" name="tahun_lulus" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="no ijazah" class="form-label">Nomer Ijazah</label>
                                                    <input type="text" class="form-control" id="nomer_ijazah"
                                                        placeholder="Nomer Ijazah" name="nomer_ijazah" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="no str" class="form-label">Nomer STR</label>
                                                    <input type="text" class="form-control" id="nomer_str"
                                                        placeholder="Nomer STR" name="nomer_str" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="expired str" class="form-label">Masa Berlaku STR</label>
                                                    <input type="date" class="form-control" id="exp_str"
                                                        name="exp_str" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="profesi" class="form-label">Profesi</label>
                                                    <input type="text" class="form-control" id="profesi"
                                                        name="profesi" placeholder="Profesi" required>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="cert profesi" class="form-label">Sertifikat
                                                        Profesi</label>
                                                    <input type="text" class="form-control" id="cert_profesi"
                                                        name="cert_profesi" placeholder="Sertifikat Profesi" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="offset-sm-0 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                {{-- <div class="tab-pane" id="pendidikan">
                                        <form class="form-horizontal">

                                            <div class="form-group row">
                                                <label for="Institusi" class="col-sm-2 col-form-label">Nama
                                                    Institusi</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputInstitusi"
                                                        placeholder="Masukan institusi" name="institusi">
                                                    @error('institusi')
                                                        <small>{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="Pendidikan" class="col-sm-2 col-form-label">Pendidikan
                                                    Terakhir</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputPendidikan"
                                                        placeholder="Pendidikan Terakhir" name="pendidikan_terakhir">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tahun lulus" class="col-sm-2 col-form-label">Tahun
                                                    Lulus</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="TahunLulus"
                                                        placeholder="Tahun Lulus" name="tahun_lulus">
                                                </div>
                                            </div>

                                        </form>
                                    </div> --}}
                                <!-- /.tab-pane -->


                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    </div>
@endsection
