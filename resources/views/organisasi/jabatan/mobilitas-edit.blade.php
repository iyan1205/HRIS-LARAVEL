@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Mobilitas Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('karyawan') }}">Karyawan</a></li>
                            <li class="breadcrumb-item active">Mobilitas Jabatan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('mobilitas.jabatan.update', ['id' => $mobilitasData->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Jabatan Sebelumnya</h3>
                                </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama Karyawan</label>
                                            <input type="text" class="form-control" id="name" value="{{ $mobilitasData->karyawan->name }}" readonly>
                                            @error('name')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="jabatan_sekarang">Jabatan </label>
                                            <input type="text" class="form-control" id="jabatan_sekarang" value="{{ $mobilitasData->jabatan_sekarang }} "readonly>
                                            @error('jabatan_sekarang')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="departemen_sekarang">Departemen </label>
                                            <input type="text" class="form-control" id="departemen_sekarang" value="{{ $mobilitasData->departemen_sekarang }} "readonly>
                                            @error('departemen_sekarang')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="unit_sekarang">Unit </label>
                                            <input type="text" class="form-control" id="departemen_sekarang" value="{{ $mobilitasData->unit_sekarang }} "readonly>
                                            @error('unit_sekarang')
                                                <small>
                                                    <p class="text-danger">{{ $message }}</p>
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                    </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-6">
                            <div class="card card-success">
                              <div class="card-header">
                                <h3 class="card-title">Jabatan Baru</h3>
                              </div>
                              <div class="card-body">
                                <div class="form-group">
                                    <label for="aspek" class="form-label">Aspek</label>
                                    <select class="form-control select2bs4" id="aspek" name="aspek"
                                        style="width: 100%;" required>
                                        <option selected value="" disabled>Pilih Aspek 
                                        </option>
                                        <option value="promosi" {{ $mobilitasData->aspek == 'promosi' ? 'selected' : '' }}>Promosi
                                        </option>
                                        <option value="mutasi" {{ $mobilitasData->aspek == 'mutasi' ? 'selected' : '' }}>Mutasi
                                        </option>
                                        <option value="demosi" {{ $mobilitasData->aspek == 'demosi' ? 'selected' : '' }}>Demosi
                                        </option>
                                        <option value="rotasi" {{ $mobilitasData->aspek == 'rotasi' ? 'selected' : '' }}>Rotasi
                                        </option>
                                    </select>
                                    @error('aspek')
                                        <small>
                                            <p class="text-danger">{{ $message }}</p>
                                        </small>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label for="jabatan_baru" class="form-label">Jabatan</label>
                                    <select class="form-control select2bs4" id="jabatan_baru" name="jabatan_baru"
                                        style="width: 100%;" required>
                                        <option selected value="" disabled>Pilih Jabatan
                                        </option>
                                        @foreach ($jabatans as $name => $label)
                                            <option value="{{ $name }}" {{ $mobilitasData->jabatan_baru == $name ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_baru')
                                        <small>
                                            <p class="text-danger">{{ $message }}</p>
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="departemen_baru" class="form-label">Departemen</label>
                                    <select class="form-control select2bs4" id="departemen_baru" name="departemen_baru"
                                        style="width: 100%;">
                                        <option selected value="" required disabled>Pilih Departemen</option>
                                        @foreach ($departemens as $name => $label)
                                            <option value="{{ $name }}" {{ $mobilitasData->departemen_baru == $name ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('departemen_baru')
                                        <small>
                                            <p class="text-danger">{{ $message }}</p>
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="unit_baru" class="form-label">Unit</label>
                                    <select class="form-control select2bs4" id="unit_baru" name="unit_baru"
                                        style="width: 100%;" required>
                                        <option selected value="" required disabled>Pilih Unit
                                        </option>
                                        @foreach ($units as $name => $label)
                                        <option value="{{ $name }}" {{ $mobilitasData->unit_baru == $name ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('unit_baru')
                                        <small>
                                            <p class="text-danger">{{ $message }}</p>
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Menjabat:</label>
                                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                          <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" name="tanggal_efektif" value="{{ $mobilitasData->tanggal_efektif }}"/>
                                          <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                      </div>
                                  </div>
                            </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right">Simpan</button>
                                </div>
                              <!-- /.card-body -->
                            </div>
                           
                          </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
        </section>
    </div> <!-- /.container-wrapper -->

@endsection
