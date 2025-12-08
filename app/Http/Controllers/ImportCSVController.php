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
use App\Jobs\ImportCSV;


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

class ImportCSVController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		return view('importcsv.index');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		// if(session()->exists('lastBatchId')){
		// 	session()->forget('lastBatchId')
		// }
		return view('importcsv.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): JsonResponse
	{
		ini_set('post_max_size', '512MB');
		ini_set('upload_max_filesize', '512MB');
		ini_set('max_input_time', '-1');
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '2048M');
		$request->validate([
												'csv.*' => ['required', 'file', 'extensions:csv']
											],[],[
												'csv.*' => 'CSV file'
											]);
		try{
			if($request->file('csv')){
				foreach ($request->file('csv') as $v) {
					$file = $v->getClientOriginalName();
					$rand = Str::random(10);
					$fileName = $rand . '_' . $file;

					// Store File in Storage Folder
					// $request->csv->storeAs('public/csv', $fileName);
					$filePath = $v->storeAs('csv', $fileName);
					// dd($filePath);
					// dd($filePath, storage_path('app/'.$filePath));

					// Store File in Public Folder
					// $request->csv->move(public_path('uploads'), $fileName);
					$data = [
										'file_original' => $file,
										'file' => $fileName
									];

					// insert data file into db
					$l = File::create($data);

					$lfile = storage_path('app/private/csv/'.$fileName);


					$header = null;
					$data = [];
					$records = array_map('str_getcsv', file($lfile));
					// dd($records);

					// split header and data
					foreach ($records as $record => $v1) {

						// sanitize all non UTF-8 from csv data and convert all of it into UTF-8 also remove BOM
						foreach ($v1 as $v2) {
							$g[$record][] = $v2;
						}

						if (!$header) {
							$header = $g[$record];
						} else {
							$data[] = $g[$record];
						}
					}
					// dd($header, $data);

					// edit int
					$data = array_chunk($data, 1000);
					// dd($data);

					// $batch = Bus::batch([])->name($fileName)->dispatch();
					$batch = Bus::batch([])->name($file)->dispatch();

					// combine header and data
					foreach ($data as $index => $values) {
						foreach ($values as $dataval) {
							// combine header with data and pickup column that we need
							$datacsv[$index][] = Arr::add(Arr::add(Arr::add(Arr::only(array_combine($header, $dataval), ['Year', 'Industry_aggregation_NZSIOC', 'Industry_code_NZSIOC', 'Industry_name_NZSIOC', 'Units', 'Variable_code', 'Variable_name', 'Variable_category', 'Value', 'Industry_code_ANZSIC06', ]), 'file_id', $l->id), 'created_at', now()), 'updated_at', now());
						}
						// dd($datacsv[$index]);

						// call queues by chunk
						// ImportCSV::dispatch($datacsv[$index]);

						// we need a progress so we use batch n comment out the queue above
						$batch->add(new ImportCSV($datacsv[$index]));
					}
				}
				session(['lastBatchId' => $batch->id]);
				Storage::delete('csv/'.$fileName);
				// redirect via ajx : important!
				return response()->json(route('progress.index', ['id' => $batch->id]));
			}
		} catch(\Exception $e){
			Log::error($e);
			return response()->json(route('importcsvs.create'));
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(File $importcsv): View
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(File $importcsv): View
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, File $importcsv): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(File $importcsv): JsonResponse
	{
		//
	}

}
