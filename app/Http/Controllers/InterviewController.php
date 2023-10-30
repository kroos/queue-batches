<?php

namespace App\Http\Controllers;

// load model
use App\Models\Interview;
use App\Models\JobBatch;

// load excel/csv/xls import/upload
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\CSVFileImport;

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
	public function index(): View
	{
		return view('create');
	}

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

					// insert data file into db
					$l = Interview::create($data);

					$lfile = storage_path('app/public/csv/'.$fileName);


					$header = null;
					$data = [];
					$records = array_map('str_getcsv', file($lfile));
					// dd($records);

					// split header and data
					foreach ($records as $record => $v1) {

						// sanitize all non UTF-8 from csv data and convert all of it into UTF-8 also remove BOM
						foreach ($v1 as $v2) {
							$g[$record][] = Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $v2));
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
							$datacsv[$index][] = Arr::add(Arr::only(array_combine($header, $dataval), ['UNIQUE_KEY', 'PRODUCT_TITLE', 'PRODUCT_DESCRIPTION', 'STYLE#', 'SANMAR_MAINFRAME_COLOR', 'SIZE', 'COLOR_NAME', 'PIECE_PRICE']), 'file_id', $l->id);
						}
						// dd($datacsv[$index]);

						// call queues by chunk
						// ProcessCSV::dispatch($datacsv[$index]);

						// we need a progress so we use batch n comment out the queue above
						$batch->add(new ProcessCSV($datacsv[$index]));
					}
				}
				session(['lastBatchId' => $batch->id]);
				return response()->json(route('interview.index', ['id' => $batch->id]));
			}
		} catch(\Exception $e){
			return $e;
		}
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
