<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user()->load([
        'karyawan.departemen', 
        'karyawan.jabatan', 
        'karyawan.unit'
    ]);

    return response()->json($user);
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/attendance', [AttendanceController::class, 'attendance']);
Route::middleware('auth:sanctum')->post('/attendance/store', [AttendanceController::class, 'store']);
Route::get('/server-time', [AttendanceController::class, 'getTime']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('attendances/today', [AttendanceController::class, 'getTodayAttendance']);
    Route::post('attendances/{id}/checkout', [AttendanceController::class, 'checkout']);
});