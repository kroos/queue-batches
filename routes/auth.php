<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\System\ActivityLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;

use App\Http\Controllers\BatchProgressController;
use App\Http\Controllers\ImportCSVController;
use App\Http\Controllers\ExportCSVController;
use App\Http\Controllers\FileEntryController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

	/* need password.confirm */
	Route::middleware('password.confirm')->group(function () {
		Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
		Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
		Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

		Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
			Route::get('/', [ActivityLogController::class, 'index'])->name('index');
			Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
			Route::delete('/{log}', [ActivityLogController::class, 'destroy'])->name('destroy');
		});
		/* add more password.confirm here */

	});

	/* need email verified */
	Route::middleware('verified')->group(function () {
		Route::get('/dashboard', function(){
			return view('dashboard');
		})->name('dashboard');

		Route::controller(BatchProgressController::class)->group(function () {
			Route::get('progress', 'progress')->name('progress');
			Route::get('/progress/index', 'index')->name('progress.index');
			Route::get('/progress/downloadCSV', 'downloadCSV')->name('progress.downloadCSV');
		});

		Route::resources([
			'importcsvs' => ImportCSVController::class,
			'exportcsvs' => ExportCSVController::class,
			'file_entries' => FileEntryController::class,
		]);
		/* add more email verified here */

	});

	/* authenticate route */
	Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
	Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
	Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
	Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
	Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
	Route::put('password', [PasswordController::class, 'update'])->name('password.update');
	Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
	/* add more authenticate route here */

});


