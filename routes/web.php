<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PoliceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SinistreController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/polices', [AdminController::class, 'polices'])->name('admin.polices');
    Route::get('/admin/polices/{police}', [AdminController::class, 'showPolice'])->name('admin.polices.show');
    Route::post('/admin/polices/{police}/validate', [AdminController::class, 'validatePolice'])->name('admin.polices.validate');
    Route::post('/admin/polices/{police}/reject', [AdminController::class, 'rejectPolice'])->name('admin.polices.reject');
    Route::get('/admin/sinistres', [AdminController::class, 'sinistres'])->name('admin.sinistres');
    Route::get('/admin/sinistres/{sinistre}', [AdminController::class, 'showSinistre'])->name('admin.sinistres.show');
    Route::post('/admin/sinistres/{sinistre}/approve', [AdminController::class, 'approveSinistre'])->name('admin.sinistres.approve');
    Route::post('/admin/sinistres/{sinistre}/reject', [AdminController::class, 'rejectSinistre'])->name('admin.sinistres.reject');

    // Routes Polices
    Route::get('/polices', [PoliceController::class, 'index'])->name('polices.index');
    Route::get('/polices/create', [PoliceController::class, 'create'])->name('polices.create');
    Route::post('/polices', [PoliceController::class, 'store'])->name('polices.store');
    Route::get('/polices/confirmation', [PoliceController::class, 'confirmation'])->name('polices.confirmation');
    Route::get('/polices/{police}', [PoliceController::class, 'show'])->name('polices.show');
    Route::get('/polices/{police}/edit', [PoliceController::class, 'edit'])->name('polices.edit');
    Route::put('/polices/{police}', [PoliceController::class, 'update'])->name('polices.update');
    Route::delete('/polices/{police}', [PoliceController::class, 'destroy'])->name('polices.destroy');

    // Routes Sinistres
    Route::resource('sinistres', SinistreController::class);

});
