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
                                            <label for="kategori" class="form-label">Kategori Jabatan:</label>
                                            <select class="form-control select2bs4" id="kategori" name="kategori" style="width: 100%;" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="Direktur" {{ old('kategori') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                                <option value="Manajer" {{ old('kategori') == 'Manajer' ? 'selected' : '' }}>Manajer</option>
                                                <option value="Kanit" {{ old('kategori') == 'Kanit' ? 'selected' : '' }}>Kanit</option>
                                                <option value="Staff" {{ old('kategori') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                            </select>
                                            @error('kategori')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div id="kategoriSelect" style="display: {{ 'Direktur','Manajer','Kanit' ? 'block' : 'none' }};">
                                            <div class="form-group">
                                                <label for="manager_id" class="form-label">Atasan Langsung:</label>
                                                <select class="form-control select2bs4" id="manager_id" name="manager_id" style="width: 100%;" required>
                                                    @foreach ($jabatans as $jabatan)
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var kategoriSelect = document.getElementById("kategori");
            var kategoriSelectDiv = document.getElementById("kategoriSelect");
    
            kategoriSelect.addEventListener("change", function() {
                var selectedValue = kategoriSelect.value;
                if (selectedValue === "Staff") {
                    kategoriSelectDiv.style.display = "none";
                } else {
                    kategoriSelectDiv.style.display = "block";
                }
            });
        });
    </script>
    
@endsection
