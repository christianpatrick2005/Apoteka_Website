<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('MainPage');
})->name('MainPage');

Route::get('/login', function () {
    return view('login');
})->name('login');


// harus login dulu
Route::middleware(['auth'])->group(function () {
    // Rute yang hanya boleh diakses Manager
    Route::middleware(['manager'])->group(function () {
        Route::get('/manage-data/pegawai', function () {
        return view('ManagePegawai');
        })->name('ManagePegawai');

        Route::get('/manage-data/pegawai/form', function () {
            return view('forms.FormPegawai');
        })->name('FormPegawai');

        Route::get('/manage-data/dokumen', function () {
            return view('ManageDokumen');
        })->name('ManageDokumen');

        Route::get('/manage-data/dokumen/form', function () {
            return view('forms.FormDokumen');
        })->name('FormDokumen');

        Route::get('/manage-data/shift', function () {
            return view('ManageShift');
        })->name('ManageShift');

        Route::get('/manage-data/shift/form', function () {
            return view('forms.FormShift');
        })->name('FormShift');
    });

    Route::middleware(['pegawai'])->group(function () {
        
    });

});
