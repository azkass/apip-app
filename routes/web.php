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
Route::get('/sop2', function () {
    return view('sop-alpine');
});
Route::get('/sop3', function () {
    return view('sop-css');
});
