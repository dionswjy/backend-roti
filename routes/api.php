<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;

// Route API Pesanan
Route::get('/pesanan', [PesananController::class, 'indexApi']);
Route::post('/pesanan', [PesananController::class, 'storeApi']);

// Route API Produk
Route::get('/produk', [ProdukController::class, 'index']);

// Route API User Management & Auth
Route::post('/register', [UserController::class, 'registerApi']);
Route::post('/login', [UserController::class, 'loginApi']);
Route::get('/users', [UserController::class, 'indexApi']);