<section>
    <header>
        
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Kata Sandi Minimal 8 karakter.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group row">
            <label for="update_password_current_password" class="col-sm-2 col-form-label">Current Password</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" style="color: red;" />
            </div>
        </div>

        <div class="form-group row">
            <label for="update_password_password" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" style="color: red;" />
            </div>
        </div>

        <div class="form-group row">
            <label for="update_password_password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" style="color: red;" />
            </div>
        </div>

        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
              <button type="submit" class="btn btn-success">Simpan</button>
            
            </div>
        </div>

        
    </form>
</section>
