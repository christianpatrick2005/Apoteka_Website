<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('MainPage');
})->name('MainPage');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/manage-data/pegawai', function () {
    return view('ManagePegawai');
})->name('ManagePegawai');

Route::get('/manage-data/dokumen', function () {
    return view('ManageDokumen');
})->name('ManageDokumen');

Route::get('/manage-data/shift', function () {
    return view('ManageShift');
})->name('ManageShift');
