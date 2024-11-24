<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/cover', function () {
    return view('cover');
});
Route::get('/sop', function () {
    return view('sop');
});
