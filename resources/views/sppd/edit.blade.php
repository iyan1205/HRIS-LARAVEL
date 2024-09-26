@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Form Edit SPPD</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">SPPD</a></li>
                            <li class="breadcrumb-item active">Edit SPPD</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <section class="content">
            <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Edit SPPD</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="{{ route('sppd.update', $sppd->id) }}" method="POST" >@csrf @method('PUT')
                                    <div class="card-body">
                                        @if(auth()->user()->hasRole('admin|Super-Admin'))
                                        <div class="form-group">
                                            <label for="user_id" class="form-label">Nama Karyawan:</label>
                                            <select class="form-control select2bs4" id="user_id" name="user_id"
                                                style="width: 100%;">
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
                                        
                                        @else
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" class="form-control" id="approver" name="level_approve" value="{{ Auth::user()->karyawan->jabatan->level_approve }}">
                                            <input type="hidden" class="form-control" id="approver" name="approver_id" value="{{ Auth::user()->karyawan->jabatan->manager_id }}">
                                        </div>
                                        {{-- Hidden Approver --}}
                                        @endif
                                        
                                        <div class="form-group">
                                            <label for="kategori_dinas">Kategori:</label>
                                            <select class="form-control select2bs4" id="kategori_dinas" name="kategori_dinas" style="width: 100%;">
                                                <option value="DOMESTIK DALAM KOTA" {{ $sppd->kategori_dinas == 'DOMESTIK DALAM KOTA' ? 'selected' : '' }}>DOMESTIK DALAM KOTA</option>
                                                <option value="DOMESTIK LUAR KOTA (MENGINAP)" {{ $sppd->kategori_dinas == 'DOMESTIK LUAR KOTA (MENGINAP)' ? 'selected' : '' }}>DOMESTIK LUAR KOTA (MENGINAP)</option>
                                                <option value="DOMESTIK LUAR KOTA (TIDAK MENGINAP)" {{ $sppd->kategori_dinas == 'DOMESTIK LUAR KOTA (TIDAK MENGINAP)' ? 'selected' : '' }}>DOMESTIK LUAR KOTA (TIDAK MENGINAP)</option>
                                                <option value="LUAR NEGERI" {{ $sppd->kategori_dinas == 'LUAR NEGERI' ? 'selected' : '' }}>LUAR NEGERI</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="fasilitas_kendaraan_group" style="display: none;">
                                            <label for="fasilitas_kendaraan">Fasilitas Kendaraan:</label>
                                            <select class="form-control select2bs4" id="fasilitas_kendaraan" name="fasilitas_kendaraan" style="width: 100%;">
                                                <option value="OPERASIONAL RS" {{ $sppd->fasilitas_kendaraan == 'OPERASIONAL RS' ? 'selected' : '' }}>OPERASIONAL RS</option>
                                                <option value="SEWA" {{ $sppd->fasilitas_kendaraan == 'SEWA' ? 'selected' : '' }}>SEWA</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="fasilitas_transportasi_group" style="display: none;">
                                            <label for="fasilitas_transportasi">Fasilitas Transportasi:</label>
                                            <select class="form-control select2bs4" name="fasilitas_transportasi" style="width: 100%;">
                                                <option value="PESAWAT" {{ $sppd->fasilitas_transportasi == 'PESAWAT ' ? 'selected' : '' }}>PESAWAT</option>
                                                <option value="KERETA API" {{ $sppd->fasilitas_transportasi == 'KERETA API' ? 'selected' : '' }}>KERETA API</option>
                                                <option value="BUS"{{ $sppd->fasilitas_transportasi == 'BUS' ? 'selected' : '' }}>BUS</option>
                                                <option value="KAPAL LAUT"{{ $sppd->fasilitas_transportasi == 'KAPAL LAUT' ? 'selected' : '' }}>KAPAL LAUT</option>
                                                <option value="TRAVEL" {{ $sppd->fasilitas_transportasi == 'TRAVEL' ? 'selected' : '' }}>TRAVEL</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="biaya_transfortasi_group" style="display: none;">
                                            <label for="biaya_transfortasi">Biaya Transportasi:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="biaya_transfortasi" name="biaya_transfortasi" value="{{ $sppd->biaya_transfortasi }}">
                                            </div>
                                        </div>

                                        <div class="form-group" id="fasilitas_akomodasi_group" style="display: none;">
                                            <label for="fasilitas_akomodasi">Fasilitas Akomodasi:</label>
                                            <select class="form-control select2bs4" name="fasilitas_akomodasi" style="width: 100%;">
                                                <option value="HOTEL" {{ $sppd->fasilitas_akomodasi == 'HOTEL ' ? 'selected' : '' }}>HOTEL</option>
                                                <option value="KOST" {{ $sppd->fasilitas_akomodasi == 'KOST ' ? 'selected' : '' }}>KOST</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="biaya_akomodasi_group" style="display: none;">
                                            <label for="biaya_akomodasi">Biaya Akomodasi:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="biaya_akomodasi" name="biaya_akomodasi" value="{{ $sppd->biaya_akomodasi }}">
                                            </div>
                                        </div>
                                        {{-- <div class="form-group" id="provinsi_tujuan_group" style="display: none;">
                                            <label for="provinsi" class="form-label">Provinsi Tujuan:</label>
                                            <select class="form-control select2bs4" id="provinsi" name="provinsi_tujuan"
                                                style="width: 100%;">
                                                @foreach ($provinces as $prov)
                                                <option value="{{ $prov['id'] }}" {{ $sppd->provinsi_tujuan == $prov['id'] ? 'selected' : '' }}>
                                                    {{ $prov['nama'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="form-group" >
                                            <label for="kota" class="form-label">Lokasi Tujuan:</label>
                                            <input class="form-control" id="kota_tujuan" name="lokasi_tujuan" value="{{ $sppd->lokasi_tujuan }}" >
                                            {{-- <select class="form-control select2bs4" id="kota" name="kota_tujuan" style="width: 100%;">
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['id'] }}" {{ $sppd->kota_tujuan == $city['id'] ? 'selected' : '' }}>
                                                        {{ $city['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="biaya_pendaftaran">Biaya Pendaftaran:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="biaya_pendaftaran" name="biaya_pendaftaran" value="{{ $sppd->biaya_pendaftaran }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="biaya_uangsaku">Biaya Uang Saku:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="biaya_uangsaku" name="biaya_uangsaku" value="{{ $sppd->biaya_uangsaku }}">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                                            <textarea class="form-control" id="rencana_kegiatan" name="rencana_kegiatan">{{ $sppd->rencana_kegiatan }}</textarea>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Tanggal Berangkat:</label>
                                                    <div class="input-group date" id="start_dateover" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#start_dateover" name="tanggal_berangkat" value="{{ $sppd->tanggal_berangkat }}"/>
                                                        <div class="input-group-append" data-target="#start_dateover" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col">
                                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali:</label>
                                                <div class="input-group date" id="end_dateover" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#end_dateover" name="tanggal_kembali" value="{{ $sppd->tanggal_kembali }}"/>
                                                    <div class="input-group-append" data-target="#end_dateover" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->

                        </div>
                    </div>
            </div>
    </section>
    </div>
  
@endsection
