<?php
namespace App\Http\Controllers;

// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// models
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

class BatchProgressController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		return view('progress.index');
	}

	public function downloadCSV(Request $request)
	{
		$filePath1 = 'csv/Industry_code_NZSIOC-'.session('Industry_code_NZSIOC').'.csv';
		$filePath2 = 'csv/generate.csv';
		// dd($filePath1, $filePath2);
			// dd(session('Industry_code_NZSIOC'), Storage::exists($filePath2));
		if (Storage::exists($filePath2)) {
			$header[-1] = ['ID', 'File_name', 'Year', 'Industry_aggregation_NZSIOC', 'Industry_code_NZSIOC', 'Industry_name_NZSIOC', 'Units', 'Variable_code', 'Variable_name', 'Variable_category', 'Value', 'Industry_code_ANZSIC06', 'Remarks', 'Created_at', 'Updated_at', 'Deleted_at'];

			// (A) READ EXISTING CSV FILE INTO ARRAY
			$csv = fopen(storage_path('app/private/'.$filePath2), 'r');
			while (($r=fgetcsv($csv)) !== false) {
				$rows[] = $r;
			}
			fclose($csv);

			// (B) PREPEND NEW ROWS
			$rows = array_merge($header, $rows);
			// dd($rows);

			// (C) SAVE UPDATED CSV
			$file = fopen(storage_path('app/private/'.$filePath1), 'w');
			foreach ($rows as $r) {
				fputcsv($file, $r);
			}
			fclose($file);

			// $url = Storage::url($filePath);
			// return redirect($url);
			session()->forget('lastBatchId');
			session()->forget('Industry_code_NZSIOC');
			Storage::delete($filePath2);

       // 🔥 Store file path in session so the redirected page can trigger download
			// session(['downloadFile' => $filePath1]);
			// session()->flash('downloadFile', $filePath1);
			return Storage::download($filePath1);
			return response()->download(storage_path('app/private/'.$filePath1))->deleteFileAfterSend(true);
			// return redirect()->route('progress.index');
		}
		session()->forget('lastBatchId');
		session()->forget('Industry_code_NZSIOC');
		return redirect()->route('progress.index');
	}

	public function getJobBatchTable(Request $request): JsonResponse
	{
		$val = JobBatch::orderBy('created_at', 'DESC')->get();
		foreach($val as $k1 => $v1) {
			$jb[$k1]['name'] = $v1->name;
			$jb[$k1]['pending'] = ($v1->pending_jobs == 0)?'No Pending':'Pending';
			$jb[$k1]['success'] = ($v1->pending_jobs == 0 && $v1->failed_jobs == 0)?'Success':(($v1->pending_jobs > 0 && $v1->failed_jobs == 0)?'Not Yet Process':(($v1->pending_jobs == 0 && $v1->failed_jobs > 0)?'Process with Fail':(($v1->pending_jobs > 0 && $v1->failed_jobs > 0)?'Process with Fail':NULL)));
			$jb[$k1]['failed'] = ($v1->failed_jobs == 0)?'No Failed':'Failed';
		}
		return response()->json($jb??[]);
	}

	public function getProgress(Request $request): JsonResponse
	{
		try {
			$batchId = $request->id ?? session('lastBatchId');
			$batch = JobBatch::find($batchId);
        // If batch is missing (already deleted), assume finished
			if (!$batch) {
				return response()->json(100);
			}
			$total = $batch->total_jobs;
			$pending = $batch->pending_jobs;
			$processed = $total - $pending;
        // Avoid division by zero
			if ($total == 0) {
				return response()->json(100);
			}
        // Calculate %
			$percent = number_format((($processed / $total) * 100), 2);
        // Force return 100 when finished
			if ($pending == 0) {
				return response()->json(100);
			}
        // Format to integer (no decimals needed)
			return response()->json($percent);
		} catch (\Exception $e) {
			Log::error($e);
			return response()->json(100);
		}
	}

}
