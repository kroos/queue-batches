<?php

namespace App\Http\Controllers;

// load model
use App\Models\Interview;

// load excel/csv/xls import/upload
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\CSVFileImport;

// load queues
use App\Jobs\ProcessCSV;


// load helper for encoding UTF-8
use \App\Helpers\Encoding;

// load validation
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

// load facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
		// try{
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

					// $lfile = storage_path('app/public/csv/'.$fileName);

					// populate data from csv to DB
					// $import = new CSVFileImport;
					// $importData = Excel::toCollection($import, $v);

					// $importData = Excel::import($import, $v);

					// straight away to queue, no need to dispatch as its been taken care by laravel excel
					// $importData = $import->queue($v);
					// dd($importData);

					$header = null;
					$dataFromCsv = [];

					// read csv file
					$records = array_map('str_getcsv', file(storage_path('app/'.$filePath)));
					// dd($records);

					// rearrange the data n make sure all the data were in UTF-8
					foreach ($records as $record) {
						// remove all non UTF-8 from data
						$record = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $record);
						if (!$header) {
							$header = $record ;
						} else {
							$dataFromCsv[] = Encoding::fixUTF8($record);
						}
					}
					// dd($header, $dataFromCsv);

					// breaking data to chunks
					$dataFromCsv = array_chunk($dataFromCsv, 1000);
					// dd($dataFromCsv);

					// looping through each chunk
					foreach ($dataFromCsv as $index => $dataCsv) {
						foreach ($dataCsv as $data) {
							$mydata[$index][] = array_combine($header, $data);
						}
						ProcessCSV::dispatch($mydata[$index]);
					}
					// dd($mydata);




				}
				// $resp = [
				// 			'status' => 'success',
				// 			'message' => 'File Upload And Process Successfully!!',
				// 		];
				// return response()->json($resp);
			}
		// } catch(\Exception $e){
		// 	$l->update(['status' => 'Failed']);
		// 	$resp = ['status' => 'error', 'message' => 'Failed to process uploaded file/s!'];
		// 	return response()->json($resp);
		// }
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
}
