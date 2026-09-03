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
                            <li class="breadcrumb-item active">Form Benefit Kartap</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('benefit-kartap.store') }}" method="POST" enctype="multipart/form-data" id="formBenefitKartap" novalidate>
                    @csrf
                    <div class="row">

                        <!-- right column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-uppercase font-weight-bold">Form Benefit Kartap</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    {{-- Alert: belum eligible sama sekali (masa tunggu 3 bulan) --}}
                                    @if(!$ringkasan['sudah_eligible'])
                                        <div class="alert alert-warning">
                                            Anda baru bisa mengajukan klaim mulai
                                            {{ $ringkasan['tanggal_boleh_klaim']->translatedFormat('d F Y') }}
                                        </div>
                                    @endif

                                    {{-- Alert: kacamata masih cooldown --}}
                                    @if(!$ringkasan['kacamata']['boleh_klaim'])
                                        <div class="alert alert-warning">
                                            Kacamata baru bisa diklaim lagi mulai
                                            {{ $ringkasan['kacamata']['tanggal_boleh_klaim_lagi']->translatedFormat('d F Y') }}
                                        </div>
                                    @endif

                                    {{-- Info sisa plafon --}}
                                    @if($ringkasan['sudah_eligible'])
                                        <div class="alert alert-info">
                                            <strong>Sisa Plafon Anda:</strong><br>
                                            MCU: Rp {{ number_format($ringkasan['mcu']['sisa'] ?? 0, 0, ',', '.') }}
                                            dari Rp {{ number_format($ringkasan['mcu']['limit'] ?? 0, 0, ',', '.') }}
                                            &nbsp;|&nbsp;
                                            Vitamin: Rp {{ number_format($ringkasan['vitamin']['sisa'] ?? 0, 0, ',', '.') }}
                                            dari Rp {{ number_format($ringkasan['vitamin']['limit'] ?? 0, 0, ',', '.') }}
                                        </div>
                                    @endif

                                    <div id="jsErrorBox" class="alert alert-danger d-none"></div>

                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
                                        <input type="text" class="form-control" id="name" placeholder="{{ Auth::user()->karyawan->name }}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="nominal_display">Nominal</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" id="nominal_display"
                                                   inputmode="numeric" autocomplete="off" placeholder="0"
                                                   {{ !$ringkasan['sudah_eligible'] ? 'disabled' : '' }} required>
                                        </div>
                                        <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal') }}">
                                        <small class="form-text text-muted" id="nominalHint"></small>
                                        @error('nominal')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_benefit">Jenis Benefit & Plafon</label>
                                        <select class="form-control" id="jenis_benefit" name="jenis_benefit"
                                                {{ !$ringkasan['sudah_eligible'] ? 'disabled' : '' }} required
                                                data-sisa-mcu="{{ $ringkasan['mcu']['sisa'] ?? 0 }}"
                                                data-sisa-vitamin="{{ $ringkasan['vitamin']['sisa'] ?? 0 }}"
                                                data-kacamata-boleh="{{ $ringkasan['kacamata']['boleh_klaim'] ? 1 : 0 }}">
                                            <option value="" disabled selected>Pilih Jenis Benefit</option>
                                            <option value="Kacamata" {{ old('jenis_benefit', $selectedJenis ?? null) == 'Kacamata' ? 'selected' : '' }}
                                                {{ !$ringkasan['kacamata']['boleh_klaim'] ? 'disabled' : '' }}>
                                                KACAMATA
                                            </option>
                                            <option value="Vitamin" {{ old('jenis_benefit', $selectedJenis ?? null) == 'Vitamin' ? 'selected' : '' }}
                                                {{ ($ringkasan['vitamin']['sisa'] ?? 0) <= 0 ? 'disabled' : '' }}>
                                                VITAMIN
                                            </option>
                                            <option value="MCU" {{ old('jenis_benefit', $selectedJenis ?? null) == 'MCU' ? 'selected' : '' }}
                                                {{ ($ringkasan['mcu']['sisa'] ?? 0) <= 0 ? 'disabled' : '' }}>
                                                MCU
                                            </option>
                                        </select>
                                        @error('jenis_benefit')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="form_pengajuan">Form Pengajuan <span class="red-star">*</span>
                                            <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="form_pengajuan"
                                                       name="form_pengajuan" accept="application/pdf" required>
                                                <label class="custom-file-label" for="form_pengajuan">Choose file</label>
                                            </div>
                                        </div>
                                        @error('form_pengajuan')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="resume">Resume <span class="red-star">*</span>
                                            <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="resume"
                                                       name="resume" accept="application/pdf" required>
                                                <label class="custom-file-label" for="resume">Choose file</label>
                                            </div>
                                        </div>
                                        @error('resume')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="bukti_pembayaran">Bukti Pembayaran <span class="red-star">*</span>
                                            <small class="form-text text-danger">Format PDF. Maksimal ukuran file 2MB.</small>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="bukti_pembayaran"
                                                       name="bukti_pembayaran" accept="application/pdf" required>
                                                <label class="custom-file-label" for="bukti_pembayaran">Choose file</label>
                                            </div>
                                        </div>
                                        @error('bukti_pembayaran')
                                            <small><p class="text-danger">{{ $message }}</p></small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="btnSubmit"
                                        {{ !$ringkasan['sudah_eligible'] ? 'disabled' : '' }}>
                                        Submit
                                    </button>
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
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    const form = document.getElementById('formBenefitKartap');
    const jenisSelect = document.getElementById('jenis_benefit');
    const nominalDisplay = document.getElementById('nominal_display'); // input teks yang diformat, TIDAK dikirim
    const nominalInput = document.getElementById('nominal'); // hidden input, nilai asli yang dikirim ke server
    const nominalHint = document.getElementById('nominalHint');
    const errorBox = document.getElementById('jsErrorBox');
    const fileInputs = ['form_pengajuan', 'resume', 'bukti_pembayaran'];

    // ==== Format ribuan untuk input Nominal ====
    function unformatAngka(str) {
        return str.replace(/\D/g, ''); // buang semua karakter selain digit
    }

    // Isi ulang tampilan format kalau ada old('nominal') (habis validasi gagal)
    if (nominalInput.value) {
        nominalDisplay.value = formatRupiah(nominalInput.value);
    }

    nominalDisplay.addEventListener('input', function () {
        const raw = unformatAngka(nominalDisplay.value);
        nominalInput.value = raw;
        nominalDisplay.value = raw ? formatRupiah(raw) : '';
    });

    // ==== Update label custom-file saat file dipilih ====
    fileInputs.forEach(function (id) {
        const input = document.getElementById(id);
        input.addEventListener('change', function () {
            const label = input.nextElementSibling;
            if (input.files.length > 0) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = 'Choose file';
            }
        });
    });

    // ==== Tampilkan sisa plafon sesuai jenis benefit yang dipilih ====
    function updateNominalHint() {
        const jenis = jenisSelect.value;
        if (jenis === 'MCU') {
            const sisa = parseFloat(jenisSelect.dataset.sisaMcu || 0);
            nominalHint.textContent = 'Sisa plafon MCU: Rp ' + formatRupiah(sisa);
        } else if (jenis === 'Vitamin') {
            const sisa = parseFloat(jenisSelect.dataset.sisaVitamin || 0);
            nominalHint.textContent = 'Sisa plafon Vitamin: Rp ' + formatRupiah(sisa);
        } else if (jenis === 'Kacamata') {
            nominalHint.textContent = 'Klaim Kacamata hanya bisa dilakukan 1x setiap 2 tahun.';
        } else {
            nominalHint.textContent = '';
        }
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    jenisSelect.addEventListener('change', updateNominalHint);
    updateNominalHint();

    // ==== Validasi sebelum submit ====
    form.addEventListener('submit', function (e) {
        const errors = [];
        errorBox.classList.add('d-none');
        errorBox.innerHTML = '';

        const jenis = jenisSelect.value;
        const nominal = parseFloat(nominalInput.value || 0);

        if (!jenis) {
            errors.push('Jenis benefit wajib dipilih.');
        }

        if (!nominal || nominal <= 0) {
            errors.push('Nominal wajib diisi dan lebih dari 0.');
        }

        if (jenis === 'MCU') {
            const sisa = parseFloat(jenisSelect.dataset.sisaMcu || 0);
            if (nominal > sisa) {
                errors.push('Nominal melebihi sisa plafon MCU (Rp ' + formatRupiah(sisa) + ').');
            }
        }

        if (jenis === 'Vitamin') {
            const sisa = parseFloat(jenisSelect.dataset.sisaVitamin || 0);
            if (nominal > sisa) {
                errors.push('Nominal melebihi sisa plafon Vitamin (Rp ' + formatRupiah(sisa) + ').');
            }
        }

        if (jenis === 'Kacamata' && jenisSelect.dataset.kacamataBoleh === '0') {
            errors.push('Klaim Kacamata sedang dalam masa cooldown, belum bisa diajukan.');
        }

        // Validasi file: wajib ada, format pdf, maksimal 2MB
        fileInputs.forEach(function (id) {
            const input = document.getElementById(id);
            const file = input.files[0];
            const label = document.querySelector('label[for="' + id + '"]').textContent.replace('*', '').trim();

            if (!file) {
                errors.push(label + ' wajib diunggah.');
                return;
            }
            if (file.type !== 'application/pdf') {
                errors.push(label + ' harus berformat PDF.');
            }
            if (file.size > MAX_FILE_SIZE) {
                errors.push(label + ' maksimal 2MB.');
            }
        });

        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = errors.map(function (msg) {
                return '<div>' + msg + '</div>';
            }).join('');
            errorBox.classList.remove('d-none');
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush