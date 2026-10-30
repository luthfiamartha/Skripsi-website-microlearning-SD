<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminGuruController;
use App\Http\Controllers\AdminKuisController;
use App\Http\Controllers\AdminMateriController;
use App\Http\Controllers\AdminModulController;
use App\Http\Controllers\AdminProgressController;
use App\Http\Controllers\AdminSiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\GuruKuisController;
use App\Http\Controllers\GuruMateriController;
use App\Http\Controllers\GuruModulController;
use App\Http\Controllers\GuruPembelajaranController;
use App\Http\Controllers\GuruProgressController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\SiswaHasilKuisController;
use App\Http\Controllers\SiswaKuisController;
use App\Http\Controllers\SiswaMandiriController;
use App\Http\Controllers\SiswaMateriController;
use App\Http\Controllers\SiswaPembelajaranController;
use App\Http\Controllers\SiswaProfilController;
use App\Http\Controllers\SiswaProgressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/register/student', [AuthController::class, 'showStudentRegister'])->name('register.student');
Route::post('/register/student', [AuthController::class, 'registerStudent'])->name('register.student.store');
Route::get('/register/teacher', [AuthController::class, 'showTeacherRegister'])->name('register.teacher');
Route::post('/register/teacher', [AuthController::class, 'registerTeacher'])->name('register.teacher.store');

Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Route Siswa
|--------------------------------------------------------------------------
*/

Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [SiswaProfilController::class, 'index'])->name('profil');
    Route::get('/profil/edit', [SiswaProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [SiswaProfilController::class, 'update'])->name('profil.update');

    Route::get('/belajar-mandiri', [SiswaMandiriController::class, 'index'])->name('mandiri.index');

    Route::get('/pembelajaran', [SiswaPembelajaranController::class, 'index'])->name('pembelajaran.index');
    Route::post('/pembelajaran/gabung', [SiswaPembelajaranController::class, 'gabung'])->name('pembelajaran.gabung');
    Route::get('/pembelajaran/{pembelajaran}', [SiswaPembelajaranController::class, 'show'])->name('pembelajaran.show');

    Route::get('/materi', [SiswaMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{materi}', [SiswaMateriController::class, 'show'])->name('materi.show');
    Route::post('/materi/{materi}/selesai', [SiswaMateriController::class, 'selesaiModul'])->name('materi.selesai');

    Route::get('/kuis', [SiswaKuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{materi}', [SiswaKuisController::class, 'show'])->name('kuis.show');
    Route::post('/kuis/{materi}', [SiswaKuisController::class, 'submit'])->name('kuis.submit');

    Route::get('/hasil-kuis', [SiswaHasilKuisController::class, 'index'])->name('hasil.index');
    Route::get('/hasil-kuis/{hasil}', [SiswaHasilKuisController::class, 'show'])->name('hasil.show');

    Route::get('/progress', [SiswaProgressController::class, 'index'])->name('progress');
});

/*
|--------------------------------------------------------------------------
| Route Guru
|--------------------------------------------------------------------------
*/

Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    Route::get('/materi', [GuruMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [GuruMateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [GuruMateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}', [GuruMateriController::class, 'show'])->name('materi.show');
    Route::get('/materi/{materi}/edit', [GuruMateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [GuruMateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [GuruMateriController::class, 'destroy'])->name('materi.destroy');

    Route::get('/modul', [GuruModulController::class, 'index'])->name('modul.index');
    Route::get('/modul/create', [GuruModulController::class, 'create'])->name('modul.create');
    Route::post('/modul', [GuruModulController::class, 'store'])->name('modul.store');
    Route::get('/modul/{modul}/edit', [GuruModulController::class, 'edit'])->name('modul.edit');
    Route::put('/modul/{modul}', [GuruModulController::class, 'update'])->name('modul.update');
    Route::delete('/modul/{modul}', [GuruModulController::class, 'destroy'])->name('modul.destroy');

    Route::get('/kuis', [GuruKuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/create', [GuruKuisController::class, 'create'])->name('kuis.create');
    Route::post('/kuis', [GuruKuisController::class, 'store'])->name('kuis.store');
    Route::get('/kuis/{kuis}/edit', [GuruKuisController::class, 'edit'])->name('kuis.edit');
    Route::put('/kuis/{kuis}', [GuruKuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{kuis}', [GuruKuisController::class, 'destroy'])->name('kuis.destroy');

    Route::get('/progress', [GuruProgressController::class, 'index'])->name('progress');

    Route::get('/pembelajaran', [GuruPembelajaranController::class, 'index'])->name('pembelajaran.index');
    Route::post('/pembelajaran', [GuruPembelajaranController::class, 'store'])->name('pembelajaran.store');
});

/*
|--------------------------------------------------------------------------
| Route Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/materi', [AdminMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [AdminMateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [AdminMateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}', [AdminMateriController::class, 'show'])->name('materi.show');
    Route::get('/materi/{materi}/edit', [AdminMateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [AdminMateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [AdminMateriController::class, 'destroy'])->name('materi.destroy');

    Route::get('/modul', [AdminModulController::class, 'index'])->name('modul.index');
    Route::get('/modul/create', [AdminModulController::class, 'create'])->name('modul.create');
    Route::post('/modul', [AdminModulController::class, 'store'])->name('modul.store');
    Route::get('/modul/{modul}/edit', [AdminModulController::class, 'edit'])->name('modul.edit');
    Route::put('/modul/{modul}', [AdminModulController::class, 'update'])->name('modul.update');
    Route::delete('/modul/{modul}', [AdminModulController::class, 'destroy'])->name('modul.destroy');

    Route::get('/kuis', [AdminKuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/create', [AdminKuisController::class, 'create'])->name('kuis.create');
    Route::post('/kuis', [AdminKuisController::class, 'store'])->name('kuis.store');
    Route::get('/kuis/{kuis}/edit', [AdminKuisController::class, 'edit'])->name('kuis.edit');
    Route::put('/kuis/{kuis}', [AdminKuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{kuis}', [AdminKuisController::class, 'destroy'])->name('kuis.destroy');

    Route::get('/siswa', [AdminSiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{siswa}/edit', [AdminSiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}', [AdminSiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [AdminSiswaController::class, 'destroy'])->name('siswa.destroy');

    Route::get('/guru', [AdminGuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/{guru}/edit', [AdminGuruController::class, 'edit'])->name('guru.edit');
    Route::put('/guru/{guru}', [AdminGuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{guru}', [AdminGuruController::class, 'destroy'])->name('guru.destroy');

    Route::get('/progress', [AdminProgressController::class, 'index'])->name('progress');
});
