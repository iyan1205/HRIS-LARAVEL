@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Form SPPD</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">SPPD</a></li>
                            <li class="breadcrumb-item active">Form SPPD</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('sppd.store') }}" method="POST" >@csrf
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                             @endif

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form SPPD</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
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
                                            <input type="hidden" class="form-control" id="approver" name="approver_id" value="{{ Auth::user()->karyawan->jabatan->manager_id }}">
                                        </div>
                                        {{-- Hidden Approver --}}
                                        @endif
                                        
                                        <div class="form-group ">
                                            <label for="rencana_kegiatan">Rencana Kegiatan:</label>
                                            <textarea class="form-control" id="rencana_kegiatan" name="rencana_kegiatan"></textarea>
                                        </div>

                                        <div class="form-group ">
                                            <label for="kota_tujuan">Kota Tujuan:</label>
                                            <input class="form-control" id="kota_tujuan" name="kota_tujuan">
                                        </div>

                                        <div class="form-group">
                                            <label for="negara_tujuan">Negara Tujuan:</label>
                                            <input class="form-control" id="negara_tujuan" name="negara_tujuan">
                                        </div>

                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Tanggal Berangkat:</label>
                                                    <div class="input-group date" id="start_dateover" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#start_dateover" name="tanggal_berangkat"/>
                                                        <div class="input-group-append" data-target="#start_dateover" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col">
                                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali:</label>
                                                <div class="input-group date" id="end_dateover" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#end_dateover" name="tanggal_kembali"/>
                                                    <div class="input-group-append" data-target="#end_dateover" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="kategori_dinas">Kategori:</label>
                                            <select class="form-control select2bs4" id="kategori_dinas" name="kategori_dinas" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Kategori</option>
                                                <option value="DOMESTIK DALAM KOTA">DOMESTIK DALAM KOTA</option>
                                                <option value="DOMESTIK LUAR KOTA (MENGINAP)">DOMESTIK LUAR KOTA (MENGINAP)</option>
                                                <option value="DOMESTIK LUAR KOTA (TIDAK MENGINAP)">DOMESTIK LUAR KOTA (TIDAK MENGINAP)</option>
                                                <option value="LUAR NEGERI">LUAR NEGERI</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="fasilitas_kendaraan_group" style="display: none;">
                                            <label for="fasilitas_kendaraan">Fasilitas Kendaraan:</label>
                                            <select class="form-control select2bs4" name="fasilitas_kendaraan" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Kendaraan</option>
                                                <option value="DINAS">DINAS</option>
                                                <option value="SEWA">SEWA</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="fasilitas_transportasi_group" style="display: none;">
                                            <label for="fasilitas_transportasi">Fasilitas Transportasi:</label>
                                            <select class="form-control select2bs4" name="fasilitas_transportasi" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Transportasi</option>
                                                <option value="PESAWAT">PESAWAT</option>
                                                <option value="KERETA API">KERETA API</option>
                                                <option value="BUS">BUS</option>
                                                <option value="KAPAL LAUT">KAPAL LAUT</option>
                                                <option value="TRAVEL">TRAVEL</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="fasilitas_akomodasi_group" style="display: none;">
                                            <label for="fasilitas_akomodasi">Fasilitas Akomodasi:</label>
                                            <select class="form-control select2bs4" name="fasilitas_akomodasi" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Akomodasi</option>
                                                <option value="HOTEL">HOTEL</option>
                                                <option value="KOST">KOST</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="biaya">Biaya:</label>
                                            <input type="number" class="form-control" id="biaya" name="biaya">
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
                </form>
            </div>
    </section>
    </div>
  
@endsection
