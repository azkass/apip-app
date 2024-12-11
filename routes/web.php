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
    $durations = $request->input('durations');
    $actor_roles = $request->input('actor_roles'); // Aktor 1 atau Aktor 2
    $actors = $request->input('actors'); // Start, Process, Decision, End

    // Pisahkan data aktor ke Aktor 1 dan Aktor 2
    $actor1 = [];
    $actor2 = [];
    foreach ($actor_roles as $index => $role) {
        if ($role === 'Aktor 1') {
            $actor1[$index] = $actors[$index];
            $actor2[$index] = '';
        } else {
            $actor1[$index] = '';
            $actor2[$index] = $actors[$index];
        }
    }

    $data = compact('activities', 'durations', 'actor1', 'actor2');
    return view('table', $data);
})->name('generate-table');

Route::post('/generate-pdf', function (Illuminate\Http\Request $request) {
    $activities = $request->input('activities');
    $durations = $request->input('durations');
    $actor1 = $request->input('actor1');
    $actor2 = $request->input('actor2');

    $data = compact('activities', 'durations', 'actor1', 'actor2');
    $html = view('table', $data)->render();
    $pdf = new PDF();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'landscape');
    $pdf->render();
    return $pdf->stream('aktivitas.pdf');
})->name('generate-pdf');

