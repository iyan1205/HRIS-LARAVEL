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
                            <li class="breadcrumb-item active">Edit Jabatan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('jabatan.update', ['id' => $jabatan->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Form Edit Jabatan</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter name" name="name" value="{{ $jabatan->name }}">
                                            @error('name')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="level" class="form-label">Level Jabatan:</label>
                                            <select class="form-control" id="level" name="level" style="width: 100%;" required>
                                                <option value="" selected>Pilih level</option>
                                                <option value="Direktur" {{ $jabatan->level == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                                <option value="Manajer" {{ $jabatan->level == 'Manajer' ? 'selected' : '' }}>Manajer</option>
                                                <option value="SPV" {{ $jabatan->level == 'SPV' ? 'selected' : '' }}>SPV</option>
                                                <option value="Kanit" {{ $jabatan->level == 'Kanit' ? 'selected' : '' }}>Kanit</option>
                                                <option value="Staff" {{ $jabatan->level == 'Staff' ? 'selected' : '' }}>Staff</option>
                                            </select>
                                            @error('level')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div id="levelForm" style="display: {{ $jabatan->level == 'Staff' ? 'none' : 'block' }};">
                                            <div class="form-group">
                                                <label for="manager_id" class="form-label">Membawahi:</label><br>
                                                @foreach($jabatans as $jabatanOption)
                                                    <div class="icheck-primary d-inline">
                                                    <input type="checkbox" id="manager_{{ $jabatanOption->id }}" name="manager_id[]" value="{{ $jabatanOption->id }}" {{ in_array($jabatanOption->id, $selectedManagerIds) ? 'checked' : '' }}>
                                                    <label for="manager_{{ $jabatanOption->id }}">{{ $jabatanOption->name }}</label><br>
                                                    </div>
                                                    @endforeach
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
                                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
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
        document.getElementById('level').addEventListener('change', function() {
            var levelForm = document.getElementById('levelForm');
            if (this.value !== 'Staff') {
                levelForm.style.display = 'block';
            } else {
                levelForm.style.display = 'none';
            }
        });
    </script>
    
    
@endsection
