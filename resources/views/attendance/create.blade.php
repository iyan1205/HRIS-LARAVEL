@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Kehadiran</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Kehadiran</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('attendance.check-in') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                <div class="card-body">
                                    
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                            <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->name }}" disabled>
                                            {{-- Hidden Approver --}}
                                        </div>

                                    <div class="form-group row">
                                        <div class="col">
                                            <label for="start_date">Tanggal:</label>
                                            <div class="input-group date" id="start_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#start_date" name="date" value="{{ now()->format('Y-m-d') }}" disabled/>
                                                <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Jam:</label>
                                            <div class="input-group date" id="time_in" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#time_in" value="{{ old('time_in', now()->format('H:i')) }}" disabled/>
                                            <div class="input-group-append" data-target="#time_in" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="time_in_photo">Upload Foto</label>
                                        <div class="custom-file">
                                            <!-- Input file yang tersembunyi -->
                                            <input type="file" class="custom-file-input" id="time_in_photo" name="time_in_photo" accept="image/*" capture="camera" required>
                                            <!-- Label tombol dengan ikon kamera -->
                                            <label class="form-control1" for="time_in_photo">
                                                <i class="fa fa-camera"></i>
                                            </label>
                                        </div>
                                        <img id="photo_preview" src="#" alt="Foto Pratinjau" style="display: none; margin-top: 5px;" width="100%">
                                    </div>
                                    
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
    </div>
    <script>
        document.getElementById('time_in_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo_preview').src = e.target.result;
                    document.getElementById('photo_preview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
