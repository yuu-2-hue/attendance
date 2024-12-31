<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 一般ユーザーログイン
Route::middleware('guest')->group(function (){
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
});

// 管理者用ログイン
Route::middleware('guest:admin')->group(function (){
    Route::get('/admin/login', function () { return view('auth.admin-login'); })->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');
});
// 管理者用ログアウト処理
Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:admin')->name('admin.logout');

Route::middleware('auth.admin')->group(function (){
    Route::get('attendance/list', [AdminController::class, 'attendanceList'])->name('admin.list');
    Route::get('attendance/{id}', [AdminController::class, 'detail'])->name('admin.detail');
    Route::get('stamp_correction_request/list', [AdminController::class, 'request'])->name('admin.request');
    Route::get('staff/list', [AdminController::class, 'staffList'])->name('admin.staff-list');
    Route::get('attendance/staff/{id}', [AdminController::class, 'staffAttendanceList'])->name('admin.staff-attendance-list');
    Route::get('stamp_correction_request/approve/{attendance_correct_request}', [AdminController::class, 'approve'])->name('admin.approve');
});

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('user.attendance');
    Route::post('/attendance/store', [AttendanceController::class, 'store']);

    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('user.list');
    Route::get('/attendance/{id}', [AttendanceController::class, 'detail'])->name('user.detail');
    Route::get('/stamp_correction_request/list', [AttendanceController::class, 'request'])->name('user.request');
});