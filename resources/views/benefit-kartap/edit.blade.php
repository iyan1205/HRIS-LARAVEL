@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Benefit Kartap</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Benefit Kartap</a></li>
                            <li class="breadcrumb-item active">Edit Benefit Kartap</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('benefit-kartap.update', $benefitKartap->id) }}" method="POST"
                      enctype="multipart/form-data" id="formEditBenefitKartap" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <!-- right column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title text-uppercase font-weight-bold">Form Edit Benefit Kartap</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div id="jsErrorBox" class="alert alert-danger d-none"></div>

                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="hidden" class="form-control" id="name" name="user_id" value="{{ Auth::id() }}">
                                        <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->karyawan->name }}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_benefit">Jenis Benefit & Plafon</label>
                                        <input type="text" class="form-control" id="jenis_benefit" value="{{ $benefitKartap->jenis_benefit }}" disabled>
                                        <small class="form-text text-muted">Jenis benefit tidak dapat diubah saat edit.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="nominal_display">Nominal</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" id="nominal_display"
                                                   inputmode="numeric" autocomplete="off" placeholder="0">
                                        </div>
                                        {{-- PENTING: default value harus dari $benefitKartap->nominal, bukan hanya old('nominal') --}}
                                        <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal', $benefitKartap->nominal) }}">
                                        <small class="form-text text-muted" id="nominalHint"></small>
                                        @error('nominal')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="form_pengajuan">Form Pengajuan
                                            <small class="form-text text-danger">Format PDF, maks 2MB. Kosongkan kalau tidak ingin mengganti file.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="form_pengajuan"
                                                       name="form_pengajuan" accept="application/pdf">
                                                <label class="custom-file-label" for="form_pengajuan">Choose file</label>
                                            </div>
                                        </div>
                                        @if($benefitKartap->form_pengajuan)
                                            <small class="d-block mt-1">
                                                File Form Pengajuan saat ini:
                                                <a href="{{ asset('storage/' . $benefitKartap->form_pengajuan) }}" target="_blank">Lihat Form</a>
                                            </small>
                                        @endif
                                        @error('form_pengajuan')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="resume">Resume
                                            <small class="form-text text-danger">Format PDF, maks 2MB. Kosongkan kalau tidak ingin mengganti file.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="resume"
                                                       name="resume" accept="application/pdf">
                                                <label class="custom-file-label" for="resume">Choose file</label>
                                            </div>
                                        </div>
                                        @if($benefitKartap->resume)
                                            <small class="d-block mt-1">
                                                File Resume saat ini:
                                                <a href="{{ asset('storage/' . $benefitKartap->resume) }}" target="_blank">Lihat Resume</a>
                                            </small>
                                        @endif
                                        @error('resume')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="bukti_pembayaran">Bukti Pembayaran
                                            <small class="form-text text-danger">Format PDF, maks 2MB. Kosongkan kalau tidak ingin mengganti file.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="bukti_pembayaran"
                                                       name="bukti_pembayaran" accept="application/pdf">
                                                <label class="custom-file-label" for="bukti_pembayaran">Choose file</label>
                                            </div>
                                        </div>
                                        @if($benefitKartap->bukti_pembayaran)
                                            <small class="d-block mt-1">
                                                File Bukti Pembayaran saat ini:
                                                <a href="{{ asset('storage/' . $benefitKartap->bukti_pembayaran) }}" target="_blank">Lihat Bukti</a>
                                            </small>
                                        @endif
                                        @error('bukti_pembayaran')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer right text-right">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
    </div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditBenefitKartap');
    const nominalDisplay = document.getElementById('nominal_display');
    const nominalInput = document.getElementById('nominal');
    const errorBox = document.getElementById('jsErrorBox');
    const MAX_FILE_SIZE = 2 * 1024 * 1024;
    const fileInputs = ['form_pengajuan', 'resume', 'bukti_pembayaran'];

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    function unformatAngka(str) {
        return str.replace(/\D/g, '');
    }

    // Isi tampilan awal dari nilai lama (sekarang selalu ada isinya)
    if (nominalInput.value) {
        nominalDisplay.value = formatRupiah(nominalInput.value);
    }

    nominalDisplay.addEventListener('input', function () {
        const raw = unformatAngka(nominalDisplay.value);
        nominalInput.value = raw;
        nominalDisplay.value = raw ? formatRupiah(raw) : '';
    });

    // Update label custom-file saat file dipilih
    fileInputs.forEach(function (id) {
        const input = document.getElementById(id);
        input.addEventListener('change', function () {
            const label = input.nextElementSibling;
            label.textContent = input.files.length > 0 ? input.files[0].name : 'Choose file';
        });
    });

    // Validasi ringan sebelum submit (file bersifat opsional saat edit)
    form.addEventListener('submit', function (e) {
        const errors = [];
        errorBox.classList.add('d-none');
        errorBox.innerHTML = '';

        const nominal = parseFloat(nominalInput.value || 0);
        if (!nominal || nominal <= 0) {
            errors.push('Nominal wajib diisi dan lebih dari 0.');
        }

        fileInputs.forEach(function (id) {
            const input = document.getElementById(id);
            const file = input.files[0];
            if (!file) return; // opsional saat edit, boleh kosong

            const label = document.querySelector('label[for="' + id + '"]').textContent.trim();
            if (file.type !== 'application/pdf') {
                errors.push(label + ' harus berformat PDF.');
            }
            if (file.size > MAX_FILE_SIZE) {
                errors.push(label + ' maksimal 2MB.');
            }
        });

        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = errors.map(function (msg) { return '<div>' + msg + '</div>'; }).join('');
            errorBox.classList.remove('d-none');
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush