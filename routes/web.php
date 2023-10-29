<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [InterviewController::class, 'index'])->name('interview.index');
Route::post('/store', [InterviewController::class, 'store'])->name('interview.store');
Route::get('/progress', [InterviewController::class, 'progress'])->name('interview.progress');


