@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cari Data On Call </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Cari Data On Call</li>
                            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Kembali</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body ">
                                <form action="{{ route('oncall-so') }}" method="GET">
                                        <div class="form-group">
                                            <label for="user_id" class="form-label">Nama Karyawan:</label>
                                            <select class="form-control select2bs4" id="user_id" name="user_id"
                                                style="width: 100%;" required>
                                                <option selected disabled>Pilih Karyawan</option>
                                                @foreach ($users as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('user_id') == $id ? 'selected' : '' }}>
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
                                            <label>Tanggal Awal (On Call):</label>
                                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#start_date" name="start_date" required/>
                                                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                        </div>
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection
