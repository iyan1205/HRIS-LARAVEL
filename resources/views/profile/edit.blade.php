@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
       
        <section class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-3">
      
                  <!-- Profile Image -->
                  <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                      <div class="text-center">
                        <img class="profile-user-img" src="{{ asset('storage/avatar/' . auth()->user()->image) }}" alt="User profile picture">
                      </div>
      
                      <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                      
                      <p class="text-muted text-center">
                        {{ optional(Auth::user()->karyawan)->nik ?? 'Admin' }}

                      </p>
      
                      <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                          <b>Tanggal Masuk :</b> 
                          {{ Auth::user()->karyawan ? \Carbon\Carbon::parse(Auth::user()->karyawan->tgl_kontrak1)->format('d/m/Y') : 'null' }}

                        </li>
                        <li class="list-group-item">
                          <b>Saldo Cuti :</b> {{ optional(Auth::user()->leave_balances)->saldo_cuti ?? '0' }}
                        </li>
                        
                        
                      </ul>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
      
                  <!-- About Me Box -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Informasi</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <strong><i class="fas fa-book mr-1"></i> Institusi</strong>
      
                      <p class="text-muted">
                        {{ optional(Auth::user()->karyawan)->pendidikan->institusi ?? 'Admin' }}
                      </p>
      
                      <hr>
      
                      <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
      
                      <p class="text-muted">{{ optional(Auth::user()->karyawan)->alamat_ktp ?? 'Admin'}}</p>
      
                      <hr>
      
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
                @role('Super-Admin|admin')
                
                @else
                
                <div class="col-md-9">
                  <div class="card">
                    <div class="card-header p-2">
                      <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#karyawan" data-toggle="tab">Data Karyawan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kontak" data-toggle="tab">Data Kontak</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pendidikan" data-toggle="tab">Data Pendidikan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#paramedis" data-toggle="tab">Data Paramedis</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pelatihan" data-toggle="tab">Data Pelatihan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#gantipassword" data-toggle="tab">Ganti Password</a></li>
                      </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                      <div class="tab-content">
                        <div class="active tab-pane" id="karyawan">
                          <!-- Post -->
                          <form class="form-horizontal" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @method('patch')
                              <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                  @csrf
                              </form>
                              <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="{{ old('name', $user->name) }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Jabatan</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="{{ $user->karyawan->jabatan->name }}" readonly>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Departemen</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="{{ $user->karyawan->departemen->name }}" readonly>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Unit</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="{{ $user->karyawan->unit->name }}" readonly>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Status Karyawan</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="@if($user->karyawan->status_karyawan == 'kartap')
                                    Karyawan Tetap
                                @else
                                    {{ ucwords($user->karyawan->status_karyawan) }}
                                @endif" readonly>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Kontrak Ke 1</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="{{ \Carbon\Carbon::parse(Auth::user()->karyawan->tgl_kontrak1)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(Auth::user()->karyawan->akhir_kontrak1)->format('d/m/Y') }}" readonly>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Kontrak Ke 2</label>
                                  <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" value="
                                    @if(Auth::user()->karyawan->tgl_kontrak2 && Auth::user()->karyawan->akhir_kontrak2)
                                    {{ \Carbon\Carbon::parse(Auth::user()->karyawan->tgl_kontrak2)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(Auth::user()->karyawan->akhir_kontrak2)->format('d/m/Y') }}
                                    @else
                                        Belum Ada Kontrak Ke 2
                                    @endif
                                    " readonly>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                  <div class="col-sm-5">
                                      <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" value="{{ old('email', $user->email) }}" readonly>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <label for="image" class="col-sm-2 col-form-label">Photo Profile</label>
                                  <div class="col-sm-5">
                                      <div class="custom-file">
                                          <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg, image/jpg, image/png">
                                          <label class="custom-file-label" for="image">Ganti Photo </label>
                                      </div>
                                      @error('image')
                                          <small>{{ $message }}</small>
                                      @enderror
                                  </div>
                                </div>
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                              <div>
                                  <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                      {{ __('Your email address is unverified.') }}

                                      <button form="send-verification"
                                          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                          {{ __('Click here to re-send the verification email.') }}
                                      </button>
                                  </p>

                                  @if (session('status') === 'verification-link-sent')
                                      <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                          {{ __('A new verification link has been sent to your email address.') }}
                                      </p>
                                  @endif
                              </div>
                              @endif
                            
                              <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                  <button type="submit" class="btn btn-success">Simpan</button>
                                
                                </div>
                              </div>
                              </form>

                          </form> 
                          
                          <!-- /.post -->
    
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="kontak">
                          <!-- The kontak -->
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">NIK KTP</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->nomer_ktp }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Nomer NPWP</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->npwp }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Telepon</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->telepon }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-5">
                                <textarea class="form-control" rows="3" readonly>{{ $user->karyawan->alamat_ktp }}</textarea>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Tempat, Tanggal Lahir</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->tempat_lahir }} , {{ \Carbon\Carbon::parse(Auth::user()->karyawan->tanggal_lahir)->format('d/m/Y') }}" readonly>
                            </div>
                          </div>
                          
                        
                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                              <div class="col-sm-5">
                                <input type="text" class="form-control" name="gender" value="{{ $user->karyawan->gender === 'P' ? 'Perempuan' : ($user->karyawan->gender === 'L' ? 'Laki - Laki' : 'Gender tidak diketahui') }}" readonly>
                                  {{-- <div class="form-check">
                                      <input class="form-check-input" type="radio" id="L" name="gender" value="L" {{ $user->karyawan->gender === 'L' ? 'checked' : '' }} disabled>
                                      <label class="form-check-label" for="L">Laki - Laki</label>
                                  </div>
                                  <div class="form-check">
                                      <input class="form-check-input" type="radio" id="P" name="gender" value="P" {{ $user->karyawan->gender === 'P' ? 'checked' : '' }} disabled>
                                      <label class="form-check-label" for="P">Perempuan</label>
                                  </div> --}}
                              </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status Perkawinan</label>
                            <div class="col-sm-5">
                              <input type="text" class="form-control" name="gender" value="{{ $user->karyawan->status_ktp === 'Menikah' ? 'Menikah' : ($user->karyawan->status_ktp === 'Belum Menikah' ? 'Belum Menikah' : 'Cerai Hidup') }}" readonly>
                                {{-- <div class="form-check">
                                    <input class="form-check-input" type="radio" id="Menikah" name="status_ktp" value="Menikah" {{ $user->karyawan->status_ktp === 'Menikah' ? 'checked' : '' }} disabled >
                                    <label class="form-check-label" for="Menikah">Menikah</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="Belum Menikah" name="status_ktp" value="Belum Menikah" {{ $user->karyawan->gender === 'Belum Menikah' ? 'checked' : '' }}disabled>
                                    <label class="form-check-label" for="Belum Menikah">Belum Menikah</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" id="Cerai Hidup" name="status_ktp" value="Cerai Hidup" {{ $user->karyawan->gender === 'Cerai Hidup' ? 'checked' : '' }}disabled>
                                  <label class="form-check-label" for="Cerai Hidup">Cerai Hidup</label>
                                </div> --}}
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="pendidikan">
                          <!-- The pendidikan -->
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Nama Institusi</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->institusi }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->pendidikan_terakhir }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Tahun Lulus</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->tahun_lulus }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Nomer Ijazah</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->nomer_ijazah }}" readonly>
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="paramedis">
                          <!-- The paramedis -->
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Nomer STR</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->nomer_str }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Masa Berlaku STR</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->exp_str }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Profesi</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->profesi }}" readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Sertifikat Profesi</label>
                            <div class="col-sm-5">
                              <input type="email" class="form-control" name="email" value="{{ $user->karyawan->pendidikan->cert_profesi }}" readonly>
                            </div>
                          </div>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="pelatihan">
                          <div class="form-group row">
                              <label for="inputPelatihan" class="col-sm-2 col-form-label">Nama Pelatihan</label>
                              <div class="col-sm-10">
                                @php $count = 1; @endphp
                                @foreach($user->karyawan->pelatihans as $pelatihan)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $count }}. {{ $pelatihan->name }}</h5> <br>
                                            <div class="d-flex justify-content-between mb-2">
                                                <!-- Tanggal Expired -->
                                                <div class="me-3">
                                                  Tanggal Expired:
                                                  <p class="m-0">{{ $pelatihan->pivot->tanggal_expired ?? 'Tidak ada tanggal expired' }}</p>
                                              </div>
                    
                                                <!-- File Sertifikat -->
                                                <div>
                                                   File Sertifikat:
                                                    @if ($pelatihan->pivot->file)
                                                        <a href="{{ route('view.certificate', basename($pelatihan->pivot->file)) }}" class="btn btn-primary btn-sm" target="_blank">Lihat Sertifikat</a>
                                                    @else
                                                        <span>Tidak ada file</span>
                                                    @endif 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                              </div>
                          </div>
                      </div>
                      
                      
                      
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="gantipassword">
                          <!-- The password -->
                          @include('profile.partials.update-password-form')
                        </div>
                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                @endrole
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div><!-- /.container-fluid -->
          </section>
    </div><!-- /.container-fluid -->
    <script>
      // Function to disable right-click context menu globally
      document.addEventListener('contextmenu', function(e) {
          e.preventDefault();
      });
      // Function to prevent Ctrl + P and Ctrl + S when the PDF is opened in a new tab
      document.addEventListener('keydown', function(e) {
          // Check if Ctrl is pressed
          if (e.ctrlKey) {
              // Prevent Ctrl + P (Print)
              if (e.key === 'p' || e.key === 'P') {
                  e.preventDefault();
              }
              // Prevent Ctrl + S (Save)
              if (e.key === 's' || e.key === 'S') {
                  e.preventDefault();
              }
          }
      });
      // Function to open PDF in a new tab and prevent default action
      function openPDF(event) {
          // Open the PDF in a new tab
          const pdfUrl = event.currentTarget.href; // Get the PDF URL from the clicked link
          window.open(pdfUrl, '_blank'); // Open in a new tab
  
          // Prevent the default link behavior
          event.preventDefault();
          return false; // Prevent any other actions
      }
  </script>
    <script>
      document.getElementById('image').addEventListener('change', function(e) {
          var fileName = e.target.files[0].name;
          var nextSibling = e.target.nextElementSibling;
          nextSibling.innerText = fileName;
      });
  </script>

  <script>
    // Cek jika status 'password-updated' diterima dari redirect atau terdapat pesan kesalahan pada bidang 'current_password'
    @if(session('status') === 'password-updated' || $errors->updatePassword->has('current_password') || $errors->updatePassword->has('password'))
        // Hilangkan kelas "active" dari tab saat ini
        document.querySelector('.nav-item .nav-link.active').classList.remove('active');

        // Tambahkan kelas "active" ke tab "gantipassword"
        document.querySelector('.nav-item a[href="#gantipassword"]').classList.add('active');

        // Cari tab konten saat ini dan sembunyikan
        document.querySelector('.tab-pane.active').classList.remove('active', 'show');

        // Tampilkan tab konten "gantipassword"
        document.getElementById('gantipassword').classList.add('active', 'show');
    @endif
</script>



  
@endsection
