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
                            <li class="breadcrumb-item"><a href="#">Benefit Kartap</a></li>
                            <li class="breadcrumb-item active">Edit Benefit Kartap</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('benefit-kartap.store') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="row">

                        <!-- right column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-uppercase font-weight-bold">Form Benefit Kartap</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                        <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->karyawan->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_pengajuan">Tanggal Pengajuan</label>
                                        <input type="date" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{{ old('tgl_pengajuan') }}" required>
                                    </div>   
                                    <div class="form-group">
                                        <label for="jenis_benefit">Jenis Benefit & Plafon</label>
                                        <select class="form-control" id="jenis_benefit" name="jenis_benefit" required>
                                            <option value="" disabled selected>Pilih Jenis Benefit</option>
                                            <option value="Kacamata" {{ old('jenis_benefit') == 'Kacamata' ? 'selected' : '' }}>KACAMATA</option>
                                            <option value="Vitamin" {{ old('jenis_benefit') == 'Vitamin' ? 'selected' : '' }}>VITAMIN</option>
                                            <option value="MCU" {{ old('jenis_benefit') == 'MCU' ? 'selected' : '' }}>MCU</option>
                                        </select>
                                        @error('jenis_benefit')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="form-group" >
                                        <label for="form_pengajuan">Form Pengajuan <span class="red-star">*</span> <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="form_pengajuan" name="form_pengajuan" accept=".pdf" value="{{ old('form_pengajuan') }}" required>
                                                <label class="custom-file-label" for="form_pengajuan">Choose file</label>
                                            </div>
                                        </div>
                                        @error('form_pengajuan')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>                                
                                    
                                    <div class="form-group" >
                                        <label for="resume">Resume <span class="red-star">*</span> <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="resume" name="resume" accept=".pdf" value="{{ old('resume') }}" required>
                                                <label class="custom-file-label" for="resume">Choose file</label>
                                            </div>
                                        </div>
                                        @error('resume')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="form-group" >
                                        <label for="bukti_pembayaran">Bukti Pembayaran <span class="red-star">*</span> <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="bukti_pembayaran" name="bukti_pembayaran" accept=".pdf" value="{{ old('bukti_pembayaran') }}" required>
                                                <label class="custom-file-label" for="bukti_pembayaran">Choose file</label>
                                            </div>
                                        </div>
                                        @error('bukti_pembayaran')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
    </div>
   
@endsection
