@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Karyawan </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('karyawan') }}">Karyawan</a></li>
                            <li class="breadcrumb-item active">Edit Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('karyawan.update', ['id' => $karyawan->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- /.Karyawan -->
                        <div class="col-md-6">
                            <div class="card card-success collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Karyawan
                                        @error('nik')
                                        <span  class="red-star">
                                                <small>{{ $message }}</small>
                                        </span>
                                        @enderror
                                    </h3> 
                                    
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="user_id">Username</label>
                                        <select class="form-control select2bs4" id="user_id" name="user_id"
                                            style="width: 100%;">
                                            @foreach ($users as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $karyawan->user_id == $id ? 'selected' : '' }}>
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
                                        <label for="nik">NIK</label>
                                        <input type="text" class="form-control" id="nik" name="nik"
                                            value="{{ old('nik', $karyawan->nik) }}">
                                        @error('nik')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $karyawan->name) }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatans">Jabatan</label>
                                        <select class="form-control select2bs4" id="jabatans" name="jabatan_id"
                                            style="width: 100%;">
                                            @foreach ($jabatans as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $karyawan->jabatan_id == $id ? 'selected' : '' }}>
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
                                        <select class="form-control select2bs4" id="departemens" name="departemen_id"
                                            style="width: 100%;">
                                            @foreach ($departemens as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $karyawan->departemen_id == $id ? 'selected' : '' }}>
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
                                        <label for="units">Unit</label>
                                        <select class="form-control select2bs4" id="Unit" name="unit_id"
                                            style="width: 100%;">
                                            @foreach ($units as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $karyawan->unit_id == $id ? 'selected' : '' }}>
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
                                        <label for="tgl_kontrak1">Tanggal Masuk Dinas</label>
                                        <input type="date" class="form-control" id="tgl_kontrak1" name="tgl_kontrak1"
                                            value="{{ old('tgl_kontrak1', $karyawan->tgl_kontrak1) }}">
                                        @error('tgl_kontrak1')
                                            <small class="text-danger"> {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="akhir_kontrak1">Masa Akhir Kontrak Ke 1</label>
                                        <input type="date" class="form-control" id="akhir_kontrak1" name="akhir_kontrak1"
                                            value="{{ old('akhir_kontrak1', $karyawan->akhir_kontrak1) }}">
                                        @error('akhir_kontrak1')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_kontrak2">Kontrak Ke 2</label>
                                        <input type="date" class="form-control" id="tgl_kontrak2" name="tgl_kontrak2"
                                            value="{{ old('tgl_kontrak2', $karyawan->tgl_kontrak2) }}">
                                        @error('tgl_kontrak2')
                                            <small class="text-danger"> {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="akhir_kontrak2">Masa Akhir Kontrak Ke 2</label>
                                        <input type="date" class="form-control" id="akhir_kontrak2" name="akhir_kontrak2"
                                            value="{{ old('akhir_kontrak2', $karyawan->akhir_kontrak2) }}">
                                        @error('akhir_kontrak2')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status_karyawan">Status Karyawan</label>
                                        <select class="form-control" name="status_karyawan" id="">
                                            <option value="kontrak" {{ $karyawan->status_karyawan == 'kontrak' ? 'selected' : '' }}>
                                                Kontrak
                                            </option>
                                            <option value="kartap" {{ $karyawan->status_karyawan == 'kartap' ? 'selected' : '' }}>
                                                Karyawan Tetap
                                            </option>
                                            <option value="fulltime" {{ $karyawan->status_karyawan == 'fulltime' ? 'selected' : '' }}>
                                                Fulltime
                                            </option>
                                            <option value="parttime" {{ $karyawan->status_karyawan == 'parttime' ? 'selected' : '' }}>
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
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="resignSelect">
                                            <option value="active" {{ $karyawan->status == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="resign" {{ $karyawan->status == 'resign' ? 'selected' : '' }}>
                                                Resign
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
                                                    <option value="{{ $id }}"
                                                        {{ $karyawan->resign_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_resign" class="col-sm-3 col-form-label">Tanggal
                                                resign:</label>
                                            <input type="date" class="form-control" name="tgl_resign"
                                                value="{{ $karyawan->tgl_resign }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.Kontak -->
                        <div class="col-md-6">
                            <div class="card card-success collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Kontak</h3>
    
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nik_ktp">NIK KTP</label>
                                        <input type="number" id="nik_ktp" name="nomer_ktp"
                                            value="{{ $karyawan->nomer_ktp }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="npwp">Nomer NPWP</label>
                                        <input type="text" id="npwp" name="npwp"
                                            value="{{ $karyawan->npwp }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="telepon">Nomer Telepon</label>
                                        <input type="text" id="telepon" name="telepon"
                                            value="{{ $karyawan->telepon }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_ktp">Alamat</label>
                                        <input type="text" id="alamat_ktp" name="alamat_ktp"
                                            value="{{ $karyawan->alamat_ktp }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="npwp">Jenis Kelamin</label>
                                        <div class="form-check" style="margin-left: 10px;">
                                            <input class="form-check-input" type="radio" id="L" name="gender" value="L" {{ $karyawan->gender === 'L' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="L">Laki - Laki</label>
                                            <input class="form-check-input" type="radio" id="P" name="gender" value="P" {{ $karyawan->gender === 'P' ? 'checked' : '' }}  
                                                style="margin-left: 6px;">
                                            <label class="form-check-label" for="P"
                                                style="margin-left: 24px;">Perempuan</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_ktp">Status Perkawinan</label>
                                        <div class="form-check" style="margin-left: 10px;">
                                            <input class="form-check-input" type="radio" id="Menikah" name="status_ktp" value="Menikah" {{ $karyawan->status_ktp == 'Menikah' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Menikah">Menikah</label>
                                            <input class="form-check-input" type="radio" id="Belum Menikah" name="status_ktp" value="Belum Menikah" {{ $karyawan->status_ktp == 'Belum Menikah' ? 'checked' : '' }} style="margin-left: 6px;">
                                            <label class="form-check-label" for="Belum Menikah" style="margin-left: 24px;">Belum Menikah</label>
                                            <input class="form-check-input" type="radio" id="Cerai Hidup" name="status_ktp" value="Cerai Hidup" {{ $karyawan->status_ktp == 'Cerai Hidup' ? 'checked' : '' }} style="margin-left: 6px;">
                                            <label class="form-check-label" for="Cerai Hidup" style="margin-left: 24px;">Cerai Hidup</label>    
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tempatLahir">Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir"
                                            value="{{ $karyawan->tempat_lahir }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggalLahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir"
                                            value="{{ $karyawan->tanggal_lahir }}">
                                    </div>
    
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.Pendidikan -->
                        <div class="col-md-6">
                            <div class="card card-success collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Pendidikan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="institusi">Nama Institusi</label>
                                        <input type="text" id="institusi" name="institusi"
                                            value="{{ $karyawan->pendidikan->institusi ?? '' }}" class="form-control">
                                        @error('institusi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                        <input type="text" class="form-control" id="pendidikan_terakhir"
                                            name="pendidikan_terakhir"
                                            value="{{ $karyawan->pendidikan->pendidikan_terakhir ?? '' }}">
                                        @error('pendidikan_terakhir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tahunLulus">Tahun Lulus</label>
                                        <input type="text" class="form-control" id="tahunLulus" name="tahun_lulus"
                                            value="{{ $karyawan->pendidikan->tahun_lulus }}">
                                        @error('tahunLulus')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nomer_ijazah">Nomer Ijazah</label>
                                        <input type="text" class="form-control" id="nomer_ijazah" name="nomer_ijazah"
                                            value="{{ $karyawan->pendidikan->nomer_ijazah }}">
                                        @error('nomer_ijazah')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
    
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.Pelatihan -->
                        <div class="col-md-6">
                            <div class="card card-success collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Pelatihan</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" id="pelatihan-container">
                                    <div class="form-group">
                                        <label for="pelatihan">Pelatihan</label>
                                        <div class="select2-purple">
                                            <select class="select2" multiple="multiple" data-placeholder="Pilih Pelatihan" name="pelatihan[]" id="pelatihan" data-dropdown-css-class="select2-purple"
                                                style="width: 100%;">
                                                @foreach($pelatihans as $pelatihan)
                                                <option value="{{ $pelatihan->id }}" {{ $karyawan->pelatihans->contains($pelatihan->id) ? 'selected' : '' }}>
                                                    {{ $pelatihan->name }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="pelatihan-details">
                                        @foreach($karyawan->pelatihans as $pelatihan)
                                        <div class="form-group pelatihan-group" id="pelatihan-{{ $pelatihan->id }}-details">
                                            <!-- Edit Nama Pelatihan -->
                                            <label for="name_{{ $pelatihan->id }}">Nama Pelatihan untuk {{ $pelatihan->name }}</label>
                                            <input type="text" name="nama_pelatihan[{{ $pelatihan->id }}]" value="{{ $pelatihan->pivot->name ?? $pelatihan->name }}" class="form-control" disabled>
                                
                                            <!-- Tanggal Expired Pelatihan -->
                                            <label for="tanggal_expired_{{ $pelatihan->id }}">Tanggal Expired untuk {{ $pelatihan->name }}</label>
                                            <input type="date" name="tanggal_expired[{{ $pelatihan->id }}]" value="{{ $pelatihan->pivot->tanggal_expired ?? '' }}" class="form-control">
                                
                                            <!-- File Upload Pelatihan -->
                                            <label for="file_{{ $pelatihan->id }}">File Sertifikat untuk {{ $pelatihan->name }}</label>
                                            @if ($pelatihan->pivot->file)
                                                <a href="{{ Storage::url($pelatihan->pivot->file) }}" target="_blank" >Lihat file saat ini</a>
                                            @endif
                                            <input type="file" name="file[{{ $pelatihan->id }}]" class="form-control" accept=".pdf">
                                            @error('file.*')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                        <hr style="border: 2px solid #000;">
                                        </div>
                                        @endforeach
                                    </div>
                                
                                    <!-- Button to Add New Pelatihan -->
                                <button type="button" id="add-pelatihan" class="btn btn-primary">Pelatihan Baru</button>

                                <!-- Hidden field to track if the user adds a new pelatihan -->
                                <input type="hidden" name="add_pelatihan" value="false" id="add_pelatihan_flag">

                                <div  class="card-body"  id="new-pelatihan-container">
                                    <!-- Form elements for new pelatihan will be dynamically added here -->
                                </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.Paramedis -->
                        <div class="col-md-6">
                            <div class="card card-success collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">Paramedis</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="no str" class="form-label">Nomer
                                            STR</label>
                                        <input type="text" class="form-control" id="nomer_str" value="{{ $karyawan->pendidikan->nomer_str }}"
                                            placeholder="Nomer STR" name="nomer_str">

                                    </div>

                                    <div class="form-group">
                                        <label for="expired str" class="form-label">Masa Berlaku STR</label>
                                        <input type="date" class="form-control" id="exp_str" value="{{ $karyawan->pendidikan->exp_str }}"
                                            name="exp_str">

                                    </div>

                                    <div class="form-group">
                                        <label for="profesi" class="form-label">Profesi</label>
                                        <input type="text" class="form-control" id="profesi"
                                            name="profesi" placeholder="Profesi" value="{{ $karyawan->pendidikan->profesi }}">
                                        @error('profesi')
                                            <small>{{ $message }}</small>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label for="cert profesi" class="form-label">Sertifikat
                                            Profesi</label>
                                        <input type="text" class="form-control"
                                            id="cert_profesi" name="cert_profesi"
                                            placeholder="Sertifikat Profesi" value="{{ $karyawan->pendidikan->cert_profesi }}">
                                        @error('cert_profesi')
                                            <small>{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                        </div> <!-- /.col -->
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('karyawan') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </section> <!-- /.content -->
    </div> <!-- /.container-wrapper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Show input fields when pelatihan is selected
        $('#pelatihan').on('change', function() {
            var selected = $(this).val();
            $('.pelatihan-group').hide(); // Hide all
            selected.forEach(function(id) {
                if ($('#pelatihan-' + id + '-details').length) {
                    // Show existing pelatihan details
                    $('#pelatihan-' + id + '-details').show();
                } else {
                    // Add new pelatihan details if not already present
                    addPelatihanDetails(id);
                }
            });
        });
    
        // Initially show fields for already selected pelatihan
        $(document).ready(function() {
            var selected = $('#pelatihan').val();
            selected.forEach(function(id) {
                if ($('#pelatihan-' + id + '-details').length) {
                    $('#pelatihan-' + id + '-details').show(); // Show existing selected on load
                }
            });
        });
    
        // Function to add new pelatihan details dynamically
        function addPelatihanDetails(id) {
            var pelatihanName = $('#pelatihan option[value="' + id + '"]').text().trim(); // Get selected pelatihan name
            var pelatihanDetails = `
                <div class="form-group pelatihan-group" id="pelatihan-` + id + `-details">
                    <!-- Edit Nama Pelatihan -->
                    <label for="name_` + id + `">Nama Pelatihan untuk ` + pelatihanName + `</label>
                    <input type="text" name="nama_pelatihan[` + id + `]" value="` + pelatihanName + `" class="form-control" disabled>
                    
                    <!-- Tanggal Expired Pelatihan -->
                    <label for="tanggal_expired_` + id + `">Tanggal Expired untuk ` + pelatihanName + `</label>
                    <input type="date" name="tanggal_expired[` + id + `]" class="form-control">
                    
                    <!-- File Upload Pelatihan -->
                    <label for="file_` + id + `">File Sertifikat untuk ` + pelatihanName + `</label>
                    <input type="file" name="file[` + id + `]" class="form-control" accept=".pdf">
                    <hr style="border: 2px solid #000;">
                </div>
            `;
            $('#pelatihan-details').append(pelatihanDetails);
        }
    
        // Add new Pelatihan input field
        $('#add-pelatihan').on('click', function() {
            // Set the flag to true when new pelatihan is added
            $('#add_pelatihan_flag').val('true');
    
            var newPelatihanFields = `
                <div class="form-group new-pelatihan-div">
                    <input type="text" name="new_pelatihan[]" class="form-control mb-2 new-pelatihan" placeholder="Nama Pelatihan Baru" required>
                    <label for="new_tanggal_expired[]">Tanggal Expired Pelatihan Baru</label>
                    <input type="date" name="new_tanggal_expired[]" class="form-control mb-2 new-expired">
                    <label for="new_file[]">File Sertifikat Pelatihan Baru</label>
                    <input type="file" name="new_file[]" class="form-control mb-2 new-file" accept=".pdf">
                    <!-- Cancel button to remove the new pelatihan div -->
                    <button type="button" class="btn btn-danger remove-pelatihan-btn">Batal</button>
                    <hr style="border: 2px solid #000;">
                </div>
            `;
            $('#new-pelatihan-container').append(newPelatihanFields);
        });
    
        // Function to remove the new pelatihan fieldset
        $(document).on('click', '.remove-pelatihan-btn', function() {
            $(this).closest('.new-pelatihan-div').remove();
        });
    </script>
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
