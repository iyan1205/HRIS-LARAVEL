@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('jabatan') }}">Jabatan</a></li>
                            <li class="breadcrumb-item active">Tambah Jabatan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('jabatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Jabatan</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama Jabatan:</label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter name" name="name" value="{{ old('name') }}">
                                            @error('name')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="level" class="form-label">Level Jabatan:</label>
                                            <select class="form-control select2bs4" id="level" name="level" style="width: 100%;" required>
                                                <option value="" disabled>Pilih Level</option>
                                                <option value="Direktur" {{ old('level') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                                <option value="Manajer" {{ old('level') == 'Manajer' ? 'selected' : '' }}>Manajer</option>
                                                <option value="SPV" {{ old('level') == 'SPV' ? 'selected' : '' }}>SPV</option>
                                                <option value="Kanit" {{ old('level') == 'Kanit' ? 'selected' : '' }}>Kanit</option>
                                                <option value="Staff" {{ old('level') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                            </select>
                                            @error('level')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="manager_id" class="form-label">Atasan Langsung:</label>
                                            <select class="form-control select2bs4" id="manager_id" name="manager_id" style="width: 100%;" required>
                                                @foreach ($jabatans as $jabatan)
                                                    <option value="" disabled>Pilih Atasan</option>
                                                    <option value="{{ $jabatan->id }}" {{ $jabatan->id == $jabatan->manager_id ? 'selected' : '' }}>
                                                        {{ $jabatan->name }}
                                                    </option>
                                                    @endforeach
                                            </select>
                                            @error('manager_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="manager_id" class="form-label">Atasan Langsung:</label>
                                            <select class="form-control select2bs4" id="manager_id" name="manager_id" style="width: 100%;" required>
                                                <option selected disabled required>Pilih Atasan</option>
                                                @foreach ($manajers as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('manager_id')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->

                        </div>
                </form>
            </div>
            <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    </div>
  
@endsection
