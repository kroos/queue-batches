<?php
use Illuminate\Support\Facades\Route;

// read API from files
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\ModelAjaxSupportController;

Route::middleware(['auth', 'auth:sanctum'])->group(function () {
	Route::controller(ModelAjaxSupportController::class)->group(function () {
		Route::get('/getActivityLogs', 'getActivityLogs')->name('getActivityLogs');
		Route::get('/getFileEntries', 'getFileEntries')->name('getFileEntries');
		Route::get('/getSelect2FileEntries', 'getSelect2FileEntries')->name('getSelect2FileEntries');
		// Route::get('/getProgress', 'getProgress')->name('getProgress');
		Route::get('/getProgress', 'getProgress')->name('getProgress');
		Route::get('/getJobBatchTable', 'getJobBatchTable')->name('getJobBatchTable');

	});

});

