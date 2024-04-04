<?php

use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [LoginController::class, 'index'])->name('auth.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['isAdmin']], function() {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    // Users Routes
    Route::prefix('master-users')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/detail', [UserController::class, 'detail'])->name('user.detail');

        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

        //Permissions
        Route::resource('permissions', PermissionController::class);
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        
        Route::delete('permissions/{permissionUuid}/delete', [PermissionController::class,'destroy']);
               
        //Roles
        Route::resource('roles', RoleController::class);
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::delete('roles/{roleUuid}/delete', [RoleController::class,'destroy']);
        Route::get('roles/{roleUuid}/give-permissions', [RoleController::class, 'addPermissionToRole']);
        Route::put('roles/{roleUuid}/give-permissions', [RoleController::class, 'givePermissionToRole']);
        
    });
  
    // Master Karyawan Routes
    Route::prefix('master-karyawan')->group(function () {
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
        Route::get('/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::post('/storePendidikan', [KaryawanController::class, 'storePendidikan'])->name('karyawan.storePendidikan');
        Route::get('/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');

        Route::get('/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::put('/update/{id}/pendidikan', [KaryawanController::class, 'update'])->name('karyawan.update.pendidikan');
        Route::delete('/delete/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        Route::get('/resign', [KaryawanController::class, 'resign'])->name('resign');
    });

    // Organisasi Routes
    Route::prefix('organisasi')->group(function () {
        // Departemen
        Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen');
        Route::get('/departemen/create', [DepartemenController::class, 'create'])->name('departemen.create');
        Route::post('/departemen/store', [DepartemenController::class, 'store'])->name('departemen.store');
        //Proses Departemen
        Route::get('/departemen/edit/{id}', [DepartemenController::class, 'edit'])->name('departemen.edit');
        Route::put('/departemen/update/{id}', [DepartemenController::class, 'update'])->name('departemen.update');
        Route::delete('/departemen/delete/{id}', [DepartemenController::class, 'destroy'])->name('departemen.delete');

        // Unit
        Route::get('/unit', [UnitController::class, 'index'])->name('unit');
        Route::get('/unit/create', [UnitController::class, 'create'])->name('unit.create');
        Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store');
        //Proses Unit
        Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
        Route::put('/unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');
        Route::delete('/unit/delete/{id}', [UnitController::class, 'destroy'])->name('unit.delete');

        // Jabatan
        Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan');
        Route::get('/jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create');
        Route::post('/jabatan/store', [JabatanController::class, 'store'])->name('jabatan.store');
        //Proses Jabatan
        Route::get('/jabatan/edit/{id}', [JabatanController::class, 'edit'])->name('jabatan.edit');
        Route::put('/jabatan/update/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
        Route::delete('/jabatan/update/{id}', [JabatanController::class, 'destroy'])->name('jabatan.delete');
    });
    
    // Pengajuan Cuti Route
    Route::get('/pengajuan-cuti', [LeaveApplicationController::class, 'index'])->name('pengajuan-cuti');
    Route::get('/approval-cuti', [LeaveApplicationController::class, 'approval'])->name('approval-cuti');

    Route::get('/pengajuan-cuti/create', [LeaveApplicationController::class, 'create'])->name('cuti.create');
    Route::post('/pengajuan-cuti/store', [LeaveApplicationController::class, 'store'])->name('cuti.store');

    Route::put('/pengajuan-cuti/{id}/approve', [LeaveApplicationController::class, 'approve'])->name('cuti.approve');
    Route::put('/pengajuan-cuti/{id}/approve', [LeaveApplicationController::class, 'approve'])->name('leave-application.approve');
    Route::put('/pengajuan-cuti/{id}/reject', [LeaveApplicationController::class, 'reject'])->name('leave-application.reject');    

    // Pelatihan
    Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan');
    Route::get('/pelatihan/create', [PelatihanController::class, 'create'])->name('pelatihan.create');
    Route::post('/pelatihan/store', [PelatihanController::class, 'store'])->name('pelatihan.store');
    //Proses Pelatihan
    Route::get('/pelatihan/edit/{id}', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
    Route::put('/pelatihan/update/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');
    Route::delete('/pelatihan/delete/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [ProfileController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



});



// Absen Route


// Gallery Route
Route::get('/gallery', [HomeController::class, 'index'])->name('gallery');




require __DIR__ . '/auth.php';
