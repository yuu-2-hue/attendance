<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\AttendanceController;

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

// ログインのカスタマイズ
Route::post('/login', function (LoginRequest $request) {
    $credentials = $request->only('email', 'password');

    if (auth()->attempt($credentials)) {
        return redirect()->intended('/attendance');
    }

    return back()->withErrors([
        'email' => 'メールアドレスまたはパスワードが正しくありません。',
    ]);
});

// 勤怠登録画面
Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance');
Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('list');
Route::get('/stamp_correction_request/list', [AttendanceController::class, 'request'])->name('request');
Route::get('/attendance/detail', [AttendanceController::class, 'detail'])->name('detail');

// Route::get('/', function () {
//     return view('welcome');
// });
