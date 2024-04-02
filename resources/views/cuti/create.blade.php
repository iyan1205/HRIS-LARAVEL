@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cuti/Izin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Cuti / Izin</a></li>
                            <li class="breadcrumb-item active">Form Pengajuan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Pengajuan</h3>
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
<<<<<<< HEAD
                                        <div class="form-group">
                                            <label for="manajer_id" class="form-label">Atasan Langsung:</label>
                                            <select class="form-control select2bs4" id="manajer_id" name="manajer_id"
                                                style="width: 100%;">
                                                @foreach ($approver as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('approver')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                            @enderror
                                        </div>
=======
                                        
>>>>>>> 87587bc79aa335c181124c666bab6b8967f3a21b
                                        @else
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                            <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->name }}" disabled>
                                        </div>
                                        {{-- Hidden Approver --}}
                                        <input type="hidden" class="form-control" id="approver" name="manager_id" value="{{ Auth::user()->karyawan->jabatan->manager_id }}">
                                        @endif
                                        
                                        
                                        <div class="form-group">
                                            <label for="leave_type" class="form-label">Jenis/Kategori:</label>
                                            <select class="form-control select2bs4" id="leave_type" name="leave_type_id"
                                                style="width: 100%;">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($leave_types as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('leave_type_id')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Tanggal Awal:</label>
                                                    <div class="input-group date" id="start_date" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#start_date" name="start_date"/>
                                                        <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col">
                                                <label for="end_date" class="form-label">Tanggal Akhir:</label>
                                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#end_date" name="end_date"/>
                                                    <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
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
                </form>
            </div>
            <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    </div>
  
@endsection
