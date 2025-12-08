<?php
namespace App\Http\Controllers;

// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// models
use App\Models\File;
use App\Models\FileEntry;
use App\Models\JobBatch;

// load db facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load validation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// load batch and queue
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ExportCSV;


// load email & notification
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;// more email

// load pdf
// use Barryvdh\DomPDF\Facade\Pdf;

// load helper
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

// load Carbon library
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use \Carbon\CarbonInterval;

use Session;
use Throwable;
use Exception;
use Log;

class ExportCSVController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		return view('exportcsv.index');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('exportcsv.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{

		$request->validate([
												'Industry_code_NZSIOC' => 'nullable',
											],
											[],
											[
												'Industry_code_NZSIOC' => 'Industry Code NZSIOC',
											]);

		try{
			$fe = FileEntry::when($request->Industry_code_NZSIOC, function(Builder $query) use ($request){
												$query->where($request->only('Industry_code_NZSIOC'));
											})
											->get()
											->toArray();
											// ->count();
			$chunk = array_chunk($fe, 300);
			// dd($chunk);
			foreach($chunk as $k1 => $v1) {
				// dd($k1, $v1);
				foreach ($v1 as $value) {
					$data[$k1][] = $value;
				}
				$dat[] = new ExportCSV($data[$k1]);
			}
			// dd($data);
			$batch = Bus::batch($dat)
						->name('Export CSV Industry_code_NZSIOC => '.$request->Industry_code_NZSIOC.' on -> '.now()->format('j M Y'))
						// ->progress(function (Batch $batch) {
						// 	// A single job has completed successfully...
						// })
						// ->then(function (Batch $batch) {
						// 	// All jobs completed successfully...
						// })
						// ->catch(function (Batch $batch, Throwable $e) {
						// 	// First batch job failure detected...
						// })
						// ->finally(function (Batch $batch) {
						// 	// The batch has finished executing...
						// })
						->dispatch();
			// set session
			session(
				['lastBatchId' => $batch->id],
				['Industry_code_NZSIOC' => $request->Industry_code_NZSIOC ?? null]
			);
			// session(['Industry_code_NZSIOC' => $request->Industry_code_NZSIOC]);
			return redirect()->route('progress.index', ['id' => $batch->id]);
		} catch(\Exception $e){
			Log::error($e);
			return redirect()->route('exportcsvs.create');
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(File $exportcsv): View
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(File $exportcsv): View
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, File $exportcsv): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(File $exportcsv): JsonResponse
	{
		//
	}

}
