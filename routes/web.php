<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function() {
    Route::get('/home', function() {
        return view('admin/home');
    });

    Route::prefix('users')->group(function() {
        Route::get('/add', function() {
            return view('admin/users/add-user');
        });
    });

    Route::prefix('doctors')->group(function() {
        Route::get('/add', function() {
            return view('admin/doctors/add-doctor');
        });
    });
});