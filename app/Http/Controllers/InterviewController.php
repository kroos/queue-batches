<?php

namespace App\Http\Controllers;

// load model
use App\Models\Interview;
use App\Models\JobBatch;

// load excel/csv/xls import/upload
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVFileImport;

// load queues
use App\Jobs\ProcessCSV;


// load helper for encoding UTF-8
use \App\Helpers\Encoding;

// load validation
use Illuminate\Http\Request;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;

// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// load storage
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

// load db facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load batch and queue
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

use Log;
use Exception;

class InterviewController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		return view('create');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreInterviewRequest $request)/*: RedirectResponse*/
	{
		ini_set('post_max_size', '512MB');
		ini_set('upload_max_filesize', '512MB');
		ini_set('max_input_time', '-1');
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '2048M');
		try{
			if($request->file('csv')){
				foreach ($request->file('csv') as $v) {
					$file = $v->getClientOriginalName();
					$currentDate = Str::random(10);
					$fileName = $currentDate . '_' . $file;

					// Store File in Storage Folder
					// $request->csv->storeAs('public/csv', $fileName);
					$filePath = $v->storeAs('public/csv', $fileName);
					// dd($filePath, storage_path('app/'.$filePath));

					// Store File in Public Folder
					// $request->csv->move(public_path('uploads'), $fileName);
					$data = ['file' => $fileName];
					$l = Interview::create($data);

					$lfile = storage_path('app/public/csv/'.$fileName);

					// populate data from csv to DB
					// $importData = Excel::toCollection(new CSVFileImport, $lfile);
					// $importData = (new CSVFileImport)->toCollection($lfile);

					// $importData = Excel::toArray(new CSVFileImport, $lfile);
					// $importData = (new CSVFileImport)->toArray($lfile);

					// $importData = Excel::import(new CSVFileImport, $lfile);
					// $importData = (new CSVFileImport)->import($lfile);

					// straight away to queue, no need to dispatch as its been taken care by laravel excel
					// $importData = Excel::queueImport(new CSVFileImport, $lfile);
					// $importData = (new CSVFileImport)->queue($lfile);
					// dd($importData);

					$batch = Bus::batch([ new ProcessCSV($lfile) ])->dispatch();
				}

				session()->put('lastBatchId', $batch->id);
				return redirect()->route('interview.progress', ['id' => $batch->id]);
			}
		} catch(\Exception $e){
			return $e;
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Interview $interview): View
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Interview $interview): View
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateInterviewRequest $request, Interview $interview): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Interview $interview): JsonResponse
	{
		//
	}

	public function progress(Request $request): JsonResponse
	{
		try {
			$batchId = $request->id ?? session()->get('lastBatchId');

			if (JobBatch::where('id', $batchId)->count()) {
				$response = JobBatch::where('id', $batchId)->first();
				return response()->json($response);
			}
		} catch (Exception $e) {
			Log::error($e);
			dd($e);
		}
	}
}
