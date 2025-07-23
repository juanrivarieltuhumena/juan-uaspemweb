<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tugas;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\TugasSelesaiController;

Route::get('/', function () {
    $tugas = Tugas::latest()->get();
    return view('welcome', compact('tugas'));
});

Route::middleware('auth')->group(function () {
    Route::post('/tugas/{tugas}/selesai', [TugasSelesaiController::class, 'tandaiSelesai'])->name('tugas.selesai');
});

Route::get('/export-tugas', function () {
    $filePath = storage_path('app/public/tugas-export.csv');

    SimpleExcelWriter::create($filePath)
        ->addRows(
            Tugas::with('mahasiswa')->get()->map(function ($tugas) {
                return [
                    'Judul' => $tugas->judul,
                    'Deskripsi' => $tugas->deskripsi,
                    'Deadline' => $tugas->deadline,
                    'Mahasiswa' => $tugas->mahasiswa->name ?? '-',
                ];
            })
        );

    return Response::download($filePath)->deleteFileAfterSend();
});
