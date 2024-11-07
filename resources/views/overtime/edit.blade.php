@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Form Edit Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('overtime') }}">Lembur</a></li>
                            <li class="breadcrumb-item active">Form Edit Lembur</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('overtime.update', $overtimes->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                             @endif

                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Form Lembur</h3>
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
                                            <label for="name">Nama</label>
                                            <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                            <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->name }}" disabled>
                                            <input type="hidden" class="form-control" id="approver" name="level_approve" value="{{ Auth::user()->karyawan->jabatan->level_approve }}">
                                        </div>
                                        {{-- Hidden Approver --}}
                                        <input type="hidden" class="form-control" id="approver" name="approver_id" value="{{ Auth::user()->karyawan->jabatan->manager_id }}">
                                        @endif
                                        
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>Tanggal Awal:</label>
                                                <div class="input-group date" id="start_dateover" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#start_dateover" name="start_date" value=" {{ $overtimes->start_date }}"/>
                                                    <div class="input-group-append" data-target="#start_dateover" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="end_dateover" class="form-label">Tanggal Akhir:</label>
                                                <div class="input-group date" id="end_dateover" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#end_dateover" name="end_date" value=" {{ $overtimes->end_date }}"/>
                                                    <div class="input-group-append" data-target="#end_dateover" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Display total duration -->
                                        <div class="form-group">
                                            <label for="total_duration">Total Jam:</label>
                                            <input type="text" id="total_duration" class="form-control" readonly value=" {{ $overtimes->interval }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan ..." maxlength="500" required >{{ $overtimes->keterangan }}</textarea>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
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
