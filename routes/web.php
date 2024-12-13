<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\PolyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.index');
});

Route::get('/login', function () {
    if(authAs('admin')) return redirect('/admin');
    if(authAs('doctor') or authAs('pharmacist')) return redirect('/workspace');
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
Route::group(['middleware' => ['auth', 'check_role:doctor,pharmacist']], function(){
    Route::resource('workspace', WorkspaceController::class)->only('index', 'update');
});

Route::prefix('admin')->group(function(){
    Route::group(['middleware' => ['auth', 'check_role:admin']], function(){
        Route::resource('', DashboardController::class)->only(['index']);
        Route::resource('users', UserController::class)->except(['create']);
        Route::resource('poly', PolyController::class)->except(['create']);
        Route::resource('medicine', MedicineController::class)->except(['create']);
    });
});
