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
                <form action="{{ route('karyawan.store') }}" method="POST" class="form-horizontal">@csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="karyawan">
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="card card-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Karyawan</h3>

                                                        </div>

                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="nik" class="form-label">NIK</label>
                                                                <input type="number" class="form-control" id="nik"
                                                                    placeholder="NIK" name="nik" required>
                                                                @error('nik')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                                <select class="form-control select2bs4" id="jabatans"
                                                                    name="jabatan_id" style="width: 100%;" required>
                                                                    <option selected disabled required>Pilih Jabatan
                                                                    </option>
                                                                    @foreach ($jabatans as $id => $name)
                                                                        <option value="{{ $id }}">
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

                                                            <div class="form-group">
                                                                <label for="departemen"
                                                                    class="form-label">Departemen</label>
                                                                <select class="form-control select2bs4" id="departemens"
                                                                    name="departemen_id" style="width: 100%;">
                                                                    <option selected disabled required>Pilih
                                                                        Departemen</option>
                                                                    @foreach ($departemens as $id => $name)
                                                                        <option value="{{ $id }}">
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

                                                            <div class="form-group">
                                                                <label for="unit" class="form-label">Unit</label>
                                                                <select class="form-control select2bs4" id="Unit"
                                                                    name="unit_id" style="width: 100%;" required>
                                                                    <option selected disabled required>Pilih Unit
                                                                    </option>
                                                                    @foreach ($units as $id => $name)
                                                                        <option value="{{ $id }}">
                                                                            {{ $name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('units')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tgl_kontrak1" class="form-label">Tanggal
                                                                    Masuk
                                                                    Dinas</label>
                                                                <input type="date" class="form-control" id="tgl_kontrak1"
                                                                    name="tgl_kontrak1"
                                                                    required>
                                                                @error('tgl_kontrak1')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="akhir_kontrak1" class="form-label">Masa
                                                                    Kontrak</label>
                                                                <input type="date" class="form-control"
                                                                    id="akhir_kontrak1" name="akhir_kontrak1" required>
                                                                @error('akhir_kontrak1')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                        </div> {{-- card-body --}}
                                                    </div> {{-- card-primary --}}
                                                </div> {{-- col --}}

                                                <div class="col-sm-6">
                                                    <div class="card card-primary">

                                                        <div class="card-header">
                                                            <h3 class="card-title">Kontak</h3>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="nama" class="form-label">Nama
                                                                    Lengkap:</label>
                                                                <input type="text" class="form-control" id="name"
                                                                    placeholder="Nama Lengkap" name="name" required>
                                                                @error('name')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="users" class="form-label">User:</label>
                                                                <select class="form-control select2bs4" id="users"
                                                                    name="user_id" style="width: 100%;" required>
                                                                    <option selected disabled required>Pilih Users</option>
                                                                    @foreach ($users as $id => $name)
                                                                        <option value="{{ $id }}">
                                                                            {{ $name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('users')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="nomer_ktp" class="form-label">NIK
                                                                    KTP</label>
                                                                <input type="number" class="form-control" id="nomer_ktp"
                                                                    placeholder="No KTP" name="nomer_ktp" required>
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
                                                                    placeholder="No Telepon" name="telepon" required>
                                                                @error('telepon')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="npwp" class="form-label">Nomer
                                                                    NPWP</label>
                                                                <input type="text" class="form-control" id="npwp"
                                                                    placeholder="No NPWP" name="npwp" required>
                                                                @error('npwp')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="alamat" class="form-label">Alamat</label>
                                                                <input type="text" class="form-control" id="alamat"
                                                                    placeholder="Alamat" name="alamat_ktp" required>
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
                                                                    <input class="form-check-input" type="radio"
                                                                        id="L" name="gender" value="L"
                                                                        required>
                                                                    <label class="form-check-label" for="L">Laki -
                                                                        Laki</label>
                                                                    <input class="form-check-input" type="radio"
                                                                        id="P" name="gender" value="P"
                                                                        style="margin-left: 6px;" required>
                                                                    <label class="form-check-label" for="p"
                                                                        style="margin-left: 24px;">Perempuan</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="status_ktp" class="form-label">Status
                                                                    Perkawinan</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        id="menikah" name="status_ktp" value="Menikah"
                                                                        required>
                                                                    <label class="form-check-label"
                                                                        for="menikah">Menikah</label>
                                                                    <input class="form-check-input" type="radio"
                                                                        id="belum_menikah" name="status_ktp"
                                                                        value="Belum Menikah" style="margin-left: 6px;"
                                                                        required>
                                                                    <label class="form-check-label" for="belum_menikah"
                                                                        style="margin-left: 24px;">Belum
                                                                        Menikah</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tempat_lahir" class="form-label">Tempat
                                                                    Lahir</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputTempatlahir" placeholder="Tempat Lahir"
                                                                    name="tempat_lahir" required>
                                                                @error('tempat_lahir')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tanggal_lahir" class="form-label">Tanggal
                                                                    Lahir</label>
                                                                <input type="date" class="form-control"
                                                                    id="inputTanggallahir" name="tanggal_lahir" required>
                                                                @error('tanggal_lahir')
                                                                    <small>
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    </small>
                                                                @enderror
                                                            </div>

                                                        </div> {{-- card-body --}}
                                                    </div> {{-- card-info --}}
                                                </div> {{-- col --}}
                                            </div> {{-- row --}}

                                            <div class="row" style="margin-top: -310px;"">
                                                <div class="col-md-6">
                                                    <div class="card card-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Pendidikan</h3>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="institusi" class="form-label">Asal
                                                                    Institusi</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputInstitusi" placeholder="institusi"
                                                                    name="institusi" required>
                                                                @error('institusi')
                                                                    <small>{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="pendidikan" class="form-label">Pendidikan
                                                                    Terakhir</label>
                                                                <input type="text" class="form-control"
                                                                    id="inputPendidikan" placeholder="Pendidikan Terakhir"
                                                                    name="pendidikan_terakhir" required>
                                                                @error('pendidikan_terakhir')
                                                                    <small>{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="tahun lulus" class="form-label">Tahun
                                                                    Lulus</label>
                                                                <input type="number" class="form-control"
                                                                    id="TahunLulus" placeholder="Tahun Lulus"
                                                                    name="tahun_lulus" required>
                                                                @error('tahun_lulus')
                                                                    <small>{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="no ijazah" class="form-label">Nomer
                                                                    Ijazah</label>
                                                                <input type="text" class="form-control"
                                                                    id="nomer_ijazah" placeholder="Nomer Ijazah"
                                                                    name="nomer_ijazah">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="no str" class="form-label">Nomer
                                                                    STR</label>
                                                                <input type="text" class="form-control" id="nomer_str"
                                                                    placeholder="Nomer STR" name="nomer_str">

                                                            </div>

                                                            <div class="form-group">
                                                                <label for="expired str" class="form-label">Masa
                                                                    Berlaku
                                                                    STR</label>
                                                                <input type="date" class="form-control" id="exp_str"
                                                                    name="exp_str">

                                                            </div>

                                                            <div class="form-group">
                                                                <label for="profesi" class="form-label">Profesi</label>
                                                                <input type="text" class="form-control" id="profesi"
                                                                    name="profesi" placeholder="Profesi">
                                                                @error('profesi')
                                                                    <small>{{ $message }}</small>
                                                                @enderror

                                                            </div>

                                                            <div class="form-group">
                                                                <label for="cert profesi" class="form-label">Sertifikat
                                                                    Profesi</label>
                                                                <input type="text" class="form-control"
                                                                    id="cert_profesi" name="cert_profesi"
                                                                    placeholder="Sertifikat Profesi">

                                                                @error('cert_profesi')
                                                                    <small>{{ $message }}</small>
                                                                @enderror

                                                            </div>
                                                        </div> {{-- card-body --}}
                                                    </div> {{-- card-primary --}}
                                                </div> {{-- col --}}
                                            </div> {{-- row --}}

                                            <div class="form-group">
                                                <div class="offset-sm-0 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>

                                        </div> {{-- tab-pane --}}
                                    </div> <!-- /.tab-content -->
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                        </div> <!-- /.col -->
                    </div> <!-- /.row -->
                </form>
            </div> <!-- /.container-fluid -->
        </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->
@endsection
