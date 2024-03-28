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
                            <li class="breadcrumb-item active">Edit Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <form action="{{ route('karyawan.update', ['id' => $karyawan->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row">
            <!-- /.Karyawan -->
              <div class="col-md-6">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Karyawan</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="inputName">Nama Lengkap</label>
                      <input type="text" id="inputName" name="name" value="{{ old('name', $karyawan->name) }}" class="form-control">
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $karyawan->nik) }}">
                        @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jabatans">Jabatan</label>
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
                    <div class="form-group">
                      <label for="departemens">Departemen</label>
                      <select class="form-control select2bs4" id="departemens" name="departemen_id" style="width: 100%;">
                        @foreach ($departemens as $id => $name)
                        <option value="{{ $id }}" {{ $karyawan->departemen_id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                    @error('departemens')
                    <small><p class="text-danger">{{ $message }}</p></small>
                    @enderror
                    </div>
                    <div class="form-group">
                      <label for="units">Unit</label>
                      <select class="form-control select2bs4" id="Unit" name="unit_id" style="width: 100%;">
                        @foreach ($units as $id => $name)
                        <option value="{{ $id }}" {{ $karyawan->unit_id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                        </select>
                        @error('units')
                        <small><p class="text-danger">{{ $message }}</p></small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tgl_kontrak1">Tanggal Masuk Dinas</label>
                        <input type="date" class="form-control" id="tgl_kontrak1" name="tgl_kontrak1" value="{{ old('tgl_kontrak1', $karyawan->tgl_kontrak1) }}">
                        @error('tgl_kontrak1')
                        <small class="text-danger"> {{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="akhir_kontrak1">Masa Akhir Kontrak Ke 1</label>
                        <input type="date" class="form-control" id="akhir_kontrak1" name="akhir_kontrak1" value="{{ old('akhir_kontrak1', $karyawan->akhir_kontrak1) }}">
                        @error('akhir_kontrak1')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tgl_kontrak2">Kontrak Ke 2</label>
                        <input type="date" class="form-control" id="tgl_kontrak2" name="tgl_kontrak2" value="{{ old('tgl_kontrak2', $karyawan->tgl_kontrak2) }}">
                        @error('tgl_kontrak2')
                        <small class="text-danger"> {{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="akhir_kontrak2">Masa Akhir Kontrak Ke 2</label>
                        <input type="date" class="form-control" id="akhir_kontrak2" name="akhir_kontrak2" value="{{ old('akhir_kontrak2', $karyawan->akhir_kontrak2) }}">
                        @error('akhir_kontrak2')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status Karyawan</label>
                        <select class="form-control" name="status" id="resignSelect">
                            <option value="active" {{ $karyawan->status == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="resign" {{ $karyawan->status == 'resign' ? 'selected' : '' }}>Resign
                            </option>
                        </select>
                        @error('status')
                        <small>
                            <p class="text-danger">{{ $message }}</p>
                        </small>
                        @enderror
                    </div>
                    <div id="resignForm" style="display: {{ $karyawan->status == 'resign' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="resign" class="col-sm-3 col-form-label">Alasan
                                Resign:</label>
                                <select class="form-control" name="resign_id" id="reason">
                                    <option selected disabled>Pilih Alasan Resign</option>
                                    @foreach ($resignReasons as $id => $name)
                                    <option value="{{ $id }}" {{ $karyawan->resign_id == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="tgl_resign" class="col-sm-3 col-form-label">Tanggal
                                resign:</label>
                                <input type="date" class="form-control" name="tgl_resign" value="{{ $karyawan->tgl_resign }}">
                        </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            <!-- /.Kontak -->
              <div class="col-md-6">
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Kontak</h3>
      
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="nik_ktp">Nomer KTP</label>
                      <input type="number" id="nik_ktp" name="nomer_ktp" value="{{ $karyawan->nomer_ktp }}" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="telepon">Nomer Telepon</label>
                      <input type="text" id="telepon" name="telepon" value="{{ $karyawan->telepon }}" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="alamat_ktp">Alamat</label>
                      <input type="text" id="alamat_ktp" name="alamat_ktp" value="{{ $karyawan->alamat_ktp }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="npwp">Jenis Kelamin</label>
                        <div class="form-check" style="margin-left: 10px;">
                        <input class="form-check-input" type="radio" id="L" name="gender" value="L" {{ $karyawan->gender === 'L' ? 'checked' : '' }} >
                        <label class="form-check-label" for="L">L</label>
                        <input class="form-check-input" type="radio" id="P" name="gender" value="P" {{ $karyawan->gender == 'P' ? 'checked' : '' }} style="margin-left: 6px;" >
                        <label class="form-check-label" for="p" style="margin-left: 24px;">P</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="npwp">Nomer NPWP</label>
                        <div class="form-check" style="margin-left: 10px;">
                            <input class="form-check-input" type="radio" id="menikah" name="status_ktp" value="{{ $karyawan->status_ktp }}" >
                            <label class="form-check-label" for="menikah">Menikah</label>
                            <input class="form-check-input" type="radio" id="belum_menikah" name="status_ktp" value="{{ $karyawan->status_ktp }}" style="margin-left: 6px;" >
                            <label class="form-check-label" for="belum_menikah" style="margin-left: 24px;">Belum Menikah</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tempatLahir">Tempat Lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir" value="{{ $karyawan->tempat_lahir }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggalLahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ $karyawan->tanggal_lahir }}">
                    </div>

                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            <!-- /.Pendidikan -->
              <div class="col-md-6">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Pendidikan</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="institusi">Nama Institusi</label>
                      <input type="text" id="institusi" name="institusi" value="{{ $karyawan->pendidikan->institusi ?? '' }}" class="form-control">
                        @error('institusi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ $karyawan->pendidikan->pendidikan_terakhir ?? '' }}">
                        @error('pendidikan_terakhir')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tahunLulus">Tahun Lulus</label>
                        <input type="text" class="form-control" id="tahunLulus" name="tahun_lulus" value="{{ $karyawan->pendidikan->tahun_lulus }}">
                        @error('tahunLulus')
                        <small>
                            <p class="text-danger">{{ $message }}</p>
                        </small>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label for="nomer_ijazah">Nomer Ijazah</label>
                      <input type="text" class="form-control" id="nomer_ijazah" name="nomer_ijazah" value="{{ $karyawan->pendidikan->nomer_ijazah }}">
                        @error('nomer_ijazah')
                        <small><p class="text-danger">{{ $message }}</p></small>
                        @enderror
                    </div>

                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            <!-- /.Paramedis -->
              <div class="col-md-6">
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Paramedis</h3>
      
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="inputEstimatedBudget">Estimated budget</label>
                      <input type="number" id="inputEstimatedBudget" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="inputSpentBudget">Total amount spent</label>
                      <input type="number" id="inputSpentBudget" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="inputEstimatedDuration">Estimated project duration</label>
                      <input type="number" id="inputEstimatedDuration" class="form-control">
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <a href="#" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Simpan" class="btn btn-success float-right">
              </div>
            </div>
            </form>
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
