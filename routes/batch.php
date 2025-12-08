<?php
use App\Http\Controllers\BatchProgressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
	Route::controller(BatchProgressController::class)->group(function () {
		Route::get('/progress/index', 'index')->name('progress.index');
		Route::get('/progress/downloadCSV', 'downloadCSV')->name('progress.downloadCSV');
	});
});

Route::middleware(['auth', 'auth:sanctum'])->group(function () {
	Route::controller(BatchProgressController::class)->group(function () {
		Route::get('/getProgress', 'getProgress')->name('getProgress');
		Route::get('/getJobBatchTable', 'getJobBatchTable')->name('getJobBatchTable');
	});
});
