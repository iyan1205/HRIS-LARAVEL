<section>
    <header>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Kata Sandi Minimal 8 karakter, menggunakan 1 huruf kapital, dan menggunakan 1 simbol.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group row">
            <label for="update_password_current_password" class="col-sm-2 col-form-label">Kata Sandi Saat Ini</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" style="color: red;" />
            </div>
        </div>

        <div class="form-group row">
            <label for="update_password_password" class="col-sm-2 col-form-label">Kata Sandi Baru</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" style="color: red;" />
                <div class="mt-2" id="password-requirements"></div>
            </div>
        </div>

        <div class="form-group row">
            <label for="update_password_password_confirmation" class="col-sm-2 col-form-label">Konfirmasi Kata Sandi</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" style="color: red;" />
                <div class="mt-2" id="password-match-message"></div>
            </div>
        </div>

        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
              <button type="submit" class="btn btn-success">Simpan</button>
            
            </div>
        </div>
        
    </form>
</section>

<script>
    var passwordInput = document.getElementById('update_password_password');

    // Tambahkan event listener untuk setiap kali input berubah
    passwordInput.addEventListener('input', function() {
        var password = this.value;

        // Setelah input berubah, cek apakah password memenuhi aturan
        var uppercaseRegex = /[A-Z]/;
        var symbolRegex = /[!@#$%^&*(),.?":{}|<>]/;

        var uppercaseValid = uppercaseRegex.test(password);
        var symbolValid = symbolRegex.test(password);
        var lengthValid = password.length >= 8;

        // Membuat ikon ceklis untuk setiap aturan
        var uppercaseIcon = uppercaseValid ? '&#10003;' : '';
        var symbolIcon = symbolValid ? '&#10003;' : '';
        var lengthIcon = lengthValid ? '&#10003;' : '';

        // Tampilkan ikon ceklis dalam elemen password-requirements
        document.getElementById('password-requirements').innerHTML = uppercaseIcon + ' Huruf Kapital ' +
            symbolIcon + ' Simbol ' +
            lengthIcon + ' 8 karakter ';

        // Setel warna teks menjadi hijau jika semua aturan terpenuhi, dan merah jika tidak
        var color = (uppercaseValid && symbolValid && lengthValid) ? 'green' : 'red';
        document.getElementById('password-requirements').style.color = color;
    });
</script>
<script>
    var passwordInput = document.getElementById('update_password_password');
    var confirmPasswordInput = document.getElementById('update_password_password_confirmation');
    var passwordMatchMessage = document.getElementById('password-match-message');

    // Tambahkan event listener untuk setiap kali input berubah pada kedua input
    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validatePassword);

    function validatePassword() {
        var password = passwordInput.value;
        var confirmPassword = confirmPasswordInput.value;

        // Periksa apakah password konfirmasi cocok dengan password yang dimasukkan
        var match = (password === confirmPassword);

        // Tampilkan pesan sesuai dengan hasil validasi
        if (confirmPassword) {
            passwordMatchMessage.style.display = 'block';
            if (match) {
                passwordMatchMessage.innerHTML = '&#10003; Kata sandi cocok';
                passwordMatchMessage.style.color = 'green';
            } else {
                passwordMatchMessage.innerHTML = '&#10007; Kata sandi tidak cocok';
                passwordMatchMessage.style.color = 'red';
            }
        } else {
            passwordMatchMessage.style.display = 'none';
        }
    }
</script>
