@extends('layout.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Pengajuan Cuti/Izin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Cuti / Izin</a></li>
                        <li class="breadcrumb-item active">Edit Pengajuan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('cuti.update', $leaveApplication->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Form Pengajuan</h3>
                            </div>
                            <div class="card-body">
                                @if(auth()->user()->hasRole('admin|Super-Admin'))
                                    <div class="form-group">
                                        <label for="user_id" class="form-label">Nama Karyawan:</label>
                                        <select class="form-control select2bs4" id="user_id" name="user_id" style="width: 100%;">
                                            @foreach ($users as $id => $name)
                                                <option value="{{ $id }}" {{ $leaveApplication->user_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" disabled>
                                        <input type="hidden" name="manager_id" value="{{ $leaveApplication->manager_id }}">
                                        <input type="hidden" name="level_approve" value="{{ $leaveApplication->level_approve }}">
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="kategori_cuti">Kategori Cuti</label>
                                    <select name="kategori_cuti" id="kategori_cuti" class="form-control select2bs4" style="width: 100%;">
                                        <option value="" disabled>Pilih Kategori Cuti</option>
                                        @foreach($kategori_cuti as $kategori => $kategoriLabel)
                                            <option value="{{ $kategori }}" {{ $kategori == $currentCategory ? 'selected' : '' }}>
                                                {{ $kategoriLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group" id="leave_type_id_container">
                                    <label for="jenis_cuti">Jenis Cuti</label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-control select2bs4" style="width: 100%;">
                                        <option value="" disabled>Pilih Jenis Cuti</option>
                                        @foreach($leaveTypes as $id => $jenis)
                                            <option value="{{ $id }}" {{ $id == $leaveApplication->leave_type_id ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                 <div id="max_amount_display" style="color:red; display:none;"></div>

                                <div class="form-group row">
                                    <div class="col">
                                        <label for="start_date">Tanggal Awal:<span class="red-star">*</span></label>
                                        <div class="input-group date" id="start_date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#start_date" name="start_date" value="{{ $leaveApplication->start_date->format('Y-m-d') }}" required/>
                                            <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Tanggal Akhir:<span class="red-star">*</span></label>
                                        <div class="input-group date" id="end_date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#end_date" name="end_date" value="{{ $leaveApplication->end_date->format('Y-m-d') }}" required/>
                                            <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Total Hari:</label>
                                    <input type="text" class="form-control" id="total_days" value="{{ $leaveApplication->total_days }}" disabled/>
                                </div>

                                <div class="form-group" id="file_upload_container" style="{{ $leaveApplication->file_upload ? 'display: block;' : 'display: none;' }}">
                                    <label for="file_upload">Upload File <span class="red-star">*</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file_upload" name="file_upload" accept=".pdf,.jpg,.jpeg,.png">
                                            <label class="custom-file-label" for="file_upload">{{ $leaveApplication->file_upload ? basename($leaveApplication->file_upload) : 'Choose file' }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="saldo_cuti">Sisa Cuti</label>
                                    <input type="text" class="form-control" id="saldo_cuti" value="{{ Auth::user()->leave_balances->saldo_cuti }}" disabled>
                                </div>

                               
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection