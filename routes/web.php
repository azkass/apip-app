<?php

use Illuminate\Support\Facades\Route;
use Dompdf\Dompdf as PDF;
require '../vendor/autoload.php';

Route::get('/', function () {
    return view('welcome');
});
Route::get('/cover', function () {
    return view('cover');
});
Route::get('/sop', function () {
    return view('sop');
});
Route::get('/sop-alpine', function () {
    return view('sop-alpine');
});
Route::get('/sop-css', function () {
    return view('sop-css');
});
Route::get('/sop-dompdf', function () {
    return view('sop-dompdf');
});

Route::post('/generate-table', function (Illuminate\Http\Request $request) {
    $activities = $request->input('activities');
    $symbols1 = $request->input('symbols1');
    $symbols2 = $request->input('symbols2');
    $durations = $request->input('durations');
    $data = compact('activities', 'symbols1', 'symbols2', 'durations');

    return view('table', $data);
})->name('generate-table');

Route::post('/generate-pdf', function (Illuminate\Http\Request $request) {
    $activities = $request->input('activities');
    $symbols1 = $request->input('symbols1');
    $symbols2 = $request->input('symbols2');
    $durations = $request->input('durations');
    $data = compact('activities', 'symbols1', 'symbols2', 'durations');

    $html = view('table', $data)->render();
    $pdf = new PDF();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'landscape');
    $pdf->render();
    return $pdf->stream('aktivitas.pdf');
})->name('generate-pdf');
