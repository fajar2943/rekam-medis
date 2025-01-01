<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\PolyController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.index');
});

Route::get('/login', function () {
    if(authAs('admin')) return redirect('/admin');
    if(authAs('doctor')) return redirect('/workspace');
    if(authAs('pharmacist')) return redirect('/pharmacy');
    if(authAs('patient')) return redirect('/appointment');
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth', 'check_role:patient']], function(){
    Route::resource('appointment', AppointmentController::class)->only('index', 'store');
});
Route::group(['middleware' => ['auth', 'check_role:doctor']], function(){
    Route::resource('workspace', WorkspaceController::class)->only('index', 'update');
    Route::post('schedule', [WorkspaceController::class, 'schedule']);
    Route::get('schedule/switch-status', [WorkspaceController::class, 'switch_status']);
    Route::get('history/{user}', [WorkspaceController::class, 'history']);
});
Route::group(['middleware' => ['auth', 'check_role:pharmacist']], function(){
    Route::get('pharmacy', [WorkspaceController::class, 'pharmacy']);
});
Route::group(['middleware' => ['auth', 'check_role:doctor,pharmacist,patient']], function(){
    Route::resource('setting', SettingController::class)->only('index', 'update');
    Route::post('profile', [SettingController::class, 'update_profile']);
    Route::post('change-password', [SettingController::class, 'change_password']);
    Route::get('checkup/{checkup}', [AppointmentController::class, 'checkup']);
    Route::put('checkup/{checkup}', [AppointmentController::class, 'update']);
    Route::put('checkup/status/{checkup}', [AppointmentController::class, 'update_status']);
});

Route::prefix('admin')->group(function(){
    Route::group(['middleware' => ['auth', 'check_role:admin']], function(){
        Route::resource('', DashboardController::class)->only(['index']);
        Route::resource('users', UserController::class)->except(['create']);
        Route::resource('poly', PolyController::class)->except(['create']);
        Route::resource('medicine', MedicineController::class)->except(['create']);
        Route::resource('schedule', ScheduleController::class)->except(['create']);
    });
});
