<?php

// TAMBAHKAN BARIS INI AGAR TULISAN 'Route' TIDAK MERAH/ERROR LAGI:
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\PegawaiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user-profile/{id}', [AuthController::class, 'getProfile']);
Route::get('/admin/peserta', [AdminController::class, 'getPesertaList']);
Route::post('/admin/generate-token/{userId}', [AdminController::class, 'generateToken']);
Route::get('/admin/bank-soal', [BankSoalController::class, 'index']);
Route::post('/admin/bank-soal', [BankSoalController::class, 'store']);
Route::post('/admin/bank-soal/pilih', [BankSoalController::class, 'updateSelection']);
Route::delete('/admin/bank-soal/{id}', [BankSoalController::class, 'destroy']);
Route::post('/ujian/mulai', [UjianController::class, 'startExam']);
Route::post('/ujian/submit/{riwayatId}', [UjianController::class, 'submitExam']);
Route::get('/ujian/review/{riwayatId}', [UjianController::class, 'getReviewJawaban']);
Route::get('/ujian/sertifikat/{riwayatId}', [UjianController::class, 'cetakSertifikat']);
Route::get('/admin/export-absensi', [AdminController::class, 'exportAbsensiPdf']);

Route::get('/admin/pegawai', [PegawaiController::class, 'index']);
Route::post('/admin/pegawai', [PegawaiController::class, 'store']);
Route::get('/admin/pegawai/{id}', [PegawaiController::class, 'show']);
Route::post('/admin/pegawai/{id}/upload-berkas', [PegawaiController::class, 'uploadBerkas']);
Route::delete('/admin/berkas/{id}', [PegawaiController::class, 'destroyBerkas']);