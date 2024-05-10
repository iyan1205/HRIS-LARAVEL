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
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                             @endif

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
                                        
                                        @else
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                            <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->name }}" disabled>
                                        </div>
                                        {{-- Hidden Approver --}}
                                        <input type="hidden" class="form-control" id="approver" name="manager_id" value="{{ Auth::user()->karyawan->jabatan->manager_id }}">
                                        <input type="hidden" class="form-control" id="approver" name="level_approve" value="{{ Auth::user()->karyawan->jabatan->level_approve }}">
                                        @endif
                                        
                                        <div class="form-group">
                                            <label for="kategori_cuti">Kategori Cuti</label>
                                            <select name="kategori_cuti" id="kategori_cuti" class="form-control select2bst4">
                                                <option value="">Pilih Kategori Cuti</option>
                                                @foreach($leaveTypes as $id => $kategori)
                                                    <option value="{{ $id }}">{{ $kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="leave_type_id_container" style="display: none;">
                                            <label for="leave_type_id">Nama Cuti</label>
                                            <select name="leave_type_id" id="leave_type_id" class="form-control select2bst4" required></select>
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
                                       
                                        <div class="form-group" id="file_upload_container" style="display: none;">
                                            <label for="file_upload">Upload File</label>
                                            <div class="input-group">
                                              <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="file_upload" name="file_upload" accept=".pdf,.jpg,.jpeg,.png" required>
                                                <label class="custom-file-label" for="file_upload">Choose file</label>
                                              </div>
                                              
                                            </div>
                                          </div>
                                        

                                        <div class="form-group">
                                            <label for="saldo_cuti">Sisa Cuti</label>
                                            <input type="text" class="form-control" id="saldo_cuti" name="saldo_cuti" value="{{ Auth::user()->leave_balances->saldo_cuti }}" disabled>
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
    <script>
        document.getElementById('file_upload').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
  
@endsection
