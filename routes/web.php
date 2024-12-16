<?php

use Illuminate\Support\Facades\Route;
// use Dompdf\Dompdf as PDF;
// require __DIR__ . '/../vendor/autoload.php';

Route::get('/', function () {
    return view('welcome', ['title' => 'Home Page']);
});
Route::get('/login', function () {
    return view('login', ['title' => 'Login Page']);
});
Route::get('/reviu', function () {
    return view('riviu', ['title' => 'Riviu Page']);
});
Route::get('/upload-instrumen', function () {
    return view('upload-instrumen', ['title' => 'Upload Instrumen Page']);
});
Route::get('/cover', function () {
    return view('cover', ['title' => 'Cover Page']);
});
Route::get('/sop-dompdf', function () {
    return view('sop-dompdf');
});
// Route::post('/generate-pdf', function (Illuminate\Http\Request $request) {
//     $activities = $request->input('activities');
//     $durations = $request->input('durations');
//     $actor1 = $request->input('actor1');
//     $actor2 = $request->input('actor2');

//     $data = compact('activities', 'durations', 'actor1', 'actor2');
//     $html = view('table', $data)->render();
//     $pdf = new PDF();
//     $pdf->loadHtml($html);
//     $pdf->setPaper('A4', 'landscape');
//     $pdf->render();
//     return $pdf->stream('aktivitas.pdf');
// })->name('generate-pdf');

use App\Http\Controllers\ActivityController;

Route::get('/activity-form', [ActivityController::class, 'showForm'])->name('activity.form');
Route::post('/generate-table', [ActivityController::class, 'generateTable'])->name('activity.generate');
