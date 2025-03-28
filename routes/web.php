<?php

use App\Http\Controllers\DataSantriController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/', 'Dashboard')->name('dashboard.index');
    Route::inertia('/data-santri', 'Data-Santri/Index')->name('data-santri');
    Route::get('/data-santri/data', [DataSantriController::class, 'data'])->name('data-santri.data');
    Route::get('/data-santri/tambah', [DataSantriController::class, 'tambah'])->name('data-santri.tambah');
    Route::post('/data-santri/store', [DataSantriController::class, 'store'])->name('data-santri.store');
    Route::get('/data-santri/edit', [DataSantriController::class, 'edit'])->name('data-santri.edit');
    Route::patch('/data-santri/patch', [DataSantriController::class, 'patch'])->name('data-santri.patch');
    Route::delete('/data-santri/softdel', [DataSantriController::class, 'softDel'])->name('data-santri.softDel');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
