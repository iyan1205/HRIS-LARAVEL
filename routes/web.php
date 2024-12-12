<?php

use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OnCallController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
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



Route::get('/', [LoginController::class, 'index'])->middleware('forceLogout')->name('auth.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth','isAdmin','verified']], function() {
    
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
        
        Route::post('/users/add-saldo-cuti', [UserController::class, 'addSaldoCutiToExistingUsers'])->name('users.add-saldo-cuti');
        
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
    Route::prefix('master')->group(function () {
        Route::get('/karyawan/active', [KaryawanController::class, 'index'])->name('karyawan');
        Route::get('/karyawan/active/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan/active/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::post('/karyawan/active/storePendidikan', [KaryawanController::class, 'storePendidikan'])->name('karyawan.storePendidikan');
        Route::get('/karyawan/active/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');

        Route::get('/karyawan/active/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/karyawan/active/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::put('/karyawan/active/update/{id}/pendidikan', [KaryawanController::class, 'update'])->name('karyawan.update.pendidikan');
        Route::delete('/karyawan/active/delete/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        Route::get('/karyawan/resign', [KaryawanController::class, 'resign'])->name('resign');
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

        Route::get('/departemen/datasoft', [DepartemenController::class, 'datasoft'])->name('departemen.datasoft');
        Route::get('/departemen/restore/{id}', [DepartemenController::class, 'restore'])->name('departemen.restore');

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


// Pelatihan
    Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan');
    Route::get('/pelatihan/create', [PelatihanController::class, 'create'])->name('pelatihan.create');
    Route::post('/pelatihan/store', [PelatihanController::class, 'store'])->name('pelatihan.store');
//Proses Pelatihan
    Route::get('/pelatihan/edit/{id}', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
    Route::put('/pelatihan/update/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');
    Route::delete('/pelatihan/delete/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.delete');

    Route::get('/view-certificate/{file}', [PelatihanController::class, 'viewCertificate'])->name('view.certificate');

    Route::prefix('Cuti')->group(function () {
        // Pengajuan Cuti Route
        Route::get('/pengajuan-cuti', [LeaveApplicationController::class, 'index'])->name('pengajuan-cuti');
        Route::get('/approval-cuti', [LeaveApplicationController::class, 'approval'])->name('approval-cuti');
        Route::get('/pengajuan-cuti/riwayat-cuti', [LeaveApplicationController::class, 'riwayat'])->name('riwayat-cuti');

        Route::get('/approval-cuti/cancel', [LeaveApplicationController::class, 'searchcuti'])->name('btn-sc.cuti');
        Route::get('/approval-cuti/cancel/search', [LeaveApplicationController::class, 'searchapprove'])->name('search-approve');
        
        Route::get('/laporan-cuti', [LeaveApplicationController::class, 'laporan'])->name('laporan-cuti');
        Route::get('/laporan-cuti/search', [LeaveApplicationController::class, 'search'])->name('laporan-search');
        
        Route::get('/pengajuan-cuti/create', [LeaveApplicationController::class, 'create'])->name('cuti.create');
        Route::post('/pengajuan-cuti/store', [LeaveApplicationController::class, 'store'])->name('cuti.store');
        
        Route::get('/pengajuan-cuti/edit/{id}', [LeaveApplicationController::class, 'edit'])->name('cuti.edit');
        Route::put('/pengajuan-cuti/update/{id}', [LeaveApplicationController::class, 'update'])->name('cuti.update');
        Route::delete('/pengajuan-cuti/delete/{id}', [LeaveApplicationController::class, 'destroy'])->name('cuti.delete');

        Route::put('/pengajuan-cuti/{id}/approve', [LeaveApplicationController::class, 'approve'])->name('leave-application.approve');
        Route::put('/pengajuan-cuti/{id}/reject', [LeaveApplicationController::class, 'reject'])->name('leave-application.reject');    
        Route::put('/pengajuan-cuti/{id}/cancel', [LeaveApplicationController::class, 'cancel'])->name('leave-application.cancel');    
        Route::put('/pengajuan-cuti/{id}/cancel_approve', [LeaveApplicationController::class, 'cancel_approve'])->name('cuti.cancel_approve');    
        Route::get('/pengajuan-cuti/edit/{kategori}', [LeaveApplicationController::class, 'getLeaveTypes']);

        // Saldo
        Route::get('/saldo-cuti', [LeaveBalanceController::class, 'index'])->name('saldo-cuti');
        Route::get('/saldo-cuti/create', [LeaveBalanceController::class, 'create'])->name('saldo-cuti.create');
        Route::post('/saldo-cuti/store', [LeaveBalanceController::class, 'store'])->name('saldo-cuti.store');
        //Proses Saldo
        Route::get('/saldo-cuti/edit/{id}', [LeaveBalanceController::class, 'edit'])->name('saldo-cuti.edit');
        Route::put('/saldo-cuti/update/{id}', [LeaveBalanceController::class, 'update'])->name('saldo-cuti.update');
        Route::delete('/saldo-cuti/delete/{id}', [LeaveBalanceController::class, 'destroy'])->name('saldo-cuti.delete');
    });
    
    //Json
    Route::get('/pengajuan-cuti/create/{kategori_cuti}', [LeaveTypeController::class, 'getLeaveTypeByCategory']);
    Route::get('/pengajuan-cuti/edit/{kategori_cuti}', [LeaveTypeController::class, 'getLeaveTypeByCategory']);
    Route::get('/pengajuan-cuti/leave-types/{id}', [LeaveTypeController::class, 'getMaxAmount']);
    


    Route::prefix('Lembur')->group( function() {
        // Overtime
        Route::get('/overtime', [OvertimeController::class, 'index'])->name('overtime');
        Route::get('/approval-overtime', [OvertimeController::class, 'approval'])->name('approval-overtime');
        Route::get('/overtime/riwayat-overtime', [OvertimeController::class, 'riwayat'])->name('overtime.riwayat');

        Route::get('/approval-overtime/cancel', [OvertimeController::class, 'searchlembur'])->name('overtime.sl');
        Route::get('/approval-overtime/cancel/search', [OvertimeController::class, 'searchapprove'])->name('search.al');

        Route::get('/laporan-overtime', [OvertimeController::class, 'laporan'])->name('laporan-lembur');
        Route::get('/laporan-overtime/search', [OvertimeController::class, 'search'])->name('overtime-search');
        
        Route::get('/overtime/create', [OvertimeController::class, 'create'])->name('overtime.create');
        Route::post('/overtime/store', [OvertimeController::class, 'store'])->name('overtime.store');
        
        Route::put('/overtime/{id}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
        Route::put('/overtime/{id}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');
    
        //Proses Overtime
        Route::get('/overtime/edit/{id}', [OvertimeController::class, 'edit'])->name('overtime.edit');
        Route::get('/overtime/show/{id}', [OvertimeController::class, 'show'])->name('overtime.show');
        Route::put('/overtime/update/{id}', [OvertimeController::class, 'update'])->name('overtime.update');
        Route::delete('/overtime/delete/{id}', [OvertimeController::class, 'destroy'])->name('overtime.delete');
    });
 
    Route::prefix('oncall')->group( function (){
        //Oncall
        Route::get('/oncall',[OnCallController::class, 'index'])->name('oncall');
        Route::get('/approval-oncall', [OnCallController::class, 'approval'])->name('approval-oncall');
        Route::get('/oncall/riwayat-oncall', [OnCallController::class, 'riwayat'])->name('oncall.riwayat');
        
        Route::get('/approval-oncall/cancel', [OnCallController::class, 'searchoncall'])->name('oncall.ol');
        Route::get('/approval-oncall/cancel/search', [OnCallController::class, 'searchapprove'])->name('oncall-so');

        Route::get('/laporan-oncall', [OnCallController::class, 'laporan'])->name('laporan-oncall');
        Route::get('/laporan-oncall/search', [OnCallController::class, 'search'])->name('oncall-search');

        Route::get('/oncall/create', [OnCallController::class, 'create'])->name('oncall.create');
        Route::post('/oncall/store', [OnCallController::class, 'store'])->name('oncall.store');
        
        Route::put('/oncall/{id}/approve', [OnCallController::class, 'approve'])->name('oncall.approve');
        Route::put('/oncall/{id}/reject', [OnCallController::class, 'reject'])->name('oncall.reject');
    
        //Proses Overtime
        Route::get('/oncall/edit/{id}', [OnCallController::class, 'edit'])->name('oncall.edit');
        Route::put('/oncall/update/{id}', [OnCallController::class, 'update'])->name('oncall.update');
        Route::delete('/oncall/delete/{id}', [OnCallController::class, 'destroy'])->name('oncall.delete');

    });
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [ProfileController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/list', [AttendanceController::class, 'list_attendance'])->name('attendance.list');
    Route::get('/attendance/find', [AttendanceController::class, 'find_attendance'])->name('attendance.find');
    Route::get('/attendance/laporan', [AttendanceController::class, 'laporan'])->name('attendance.laporan');
    Route::get('/attendance/laporan/find', [AttendanceController::class, 'find_attendance_report'])->name('attendance.find.report');
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');

    // Menghitung jumlah pengajuan realtime
    Route::get('/api/pending-count', [LeaveApplicationController::class, 'getPendingCount'])->name('api.pending-count');
    Route::get('/api/over-count', [OvertimeController::class, 'getOverCount'])->name('api.over-count');
    Route::get('/api/oncall-count', [OnCallController::class, 'getOncallCount'])->name('api.oncall-count');
});




require __DIR__ . '/auth.php';
