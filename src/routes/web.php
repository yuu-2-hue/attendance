<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminListController;
use App\Http\Controllers\AdminDetailController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminRequestController;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\UserRequestController;

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

// 管理者用
Route::middleware('auth.admin')->prefix('admin')->group(function (){
    Route::get('/attendance/list', [AdminListController::class, 'attendanceList'])->name('admin.list');
    Route::post('/attendance/list/change_day', [AdminListController::class, 'changeDay']);

    Route::get('/attendance/{id}', [AdminDetailController::class, 'detail'])->name('admin.detail');
    Route::post('/attendance/{id}/retouch', [AdminDetailController::class, 'retouch']);

    Route::get('/staff/list', [AdminStaffController::class, 'staffList'])->name('admin.staff-list');
    Route::get('/attendance/staff/{id}', [AdminStaffController::class, 'staffAttendanceList'])->name('admin.staff-attendance-list');
    Route::get('/attendance/staff/{id}/changeMonth', [AdminStaffController::class, 'changeMonth']);
    Route::post('/export', [AdminStaffController::class, 'export']);

    Route::get('/stamp_correction_request/list', [AdminRequestController::class, 'request'])->name('admin.request');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminRequestController::class, 'approve'])->name('admin.approve');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [AdminRequestController::class, 'update']);
});

// 一般ユーザー用
Route::middleware('verified')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('user.attendance');
    Route::post('/attendance/store', [AttendanceController::class, 'store']);

    Route::get('/attendance/list', [UserListController::class, 'list'])->name('user.list');
    Route::get('/attendance/list/change_month', [UserListController::class, 'changeMonth']);

    Route::get('/attendance/{id}', [UserDetailController::class, 'detail'])->name('user.detail');
    Route::post('/attendance/{id}/retouch', [UserDetailController::class, 'retouch']);

    Route::get('/stamp_correction_request/list', [UserRequestController::class, 'request'])->name('user.request');
});