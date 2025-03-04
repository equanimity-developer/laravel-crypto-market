<?php

use App\Http\Controllers\CryptoController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CryptoController::class, 'index'])->name('crypto.index');

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
