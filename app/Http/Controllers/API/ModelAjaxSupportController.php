<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

// load batch and queue
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

// for controller output
use Illuminate\Http\JsonResponse;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Redirect;
// use Illuminate\Http\Response;
// use Illuminate\View\View;

// models
use App\Models\{
	YesNoOption, ActivityLog, JobBatch, FileEntry
};

// load db facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load validation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use {{ namespacedRequests }}

// load batch and queue
// use Illuminate\Bus\Batch;
// use Illuminate\Support\Facades\Bus;

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

class ModelAjaxSupportController extends Controller
{
	public function getActivityLogs(Request $request): JsonResponse
	{
		$columns = [
			0 => 'id',
			1 => 'event',
			2 => 'model_type',
			3 => 'user_id',
			4 => 'ip_address',
			5 => 'created_at',
			6 => 'route_name',
			7 => 'model_id',
		];

		$query = ActivityLog::select([
			'id',
			'event',
			'model_type',
			'user_id',
			'ip_address',
			'created_at',
			'route_name',
			'model_id',
		]);

		if ($request->search_value) {
			$search = $request->search_value;

			$query->where(function ($q) use ($search) {
				$q->where('model_type', 'LIKE', "%{$search}%")
				->orWhere('ip_address', 'LIKE', "%{$search}%")
				->orWhere('user_id', 'LIKE', "%{$search}%");
				// ->orWhereHas('belongstouser', function ($uq) use ($search) {
				// 	$uq->where('name', 'LIKE', "{$search}%");
				// });
			});
		}

		$totalRecords = ActivityLog::count();
		$filteredRecords = (clone $query)->count();

		$orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
		$orderDir = $request->order[0]['dir'] ?? 'desc';

		$data = $query
		->orderBy($orderColumn, $orderDir)
		->skip($request->start)
		->take($request->length)
		->get();

		return response()->json([
			'draw' => intval($request->draw),
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $data,
		]);
	}

	public function getJobBatchTable(Request $request): JsonResponse
	{
		$values = JobBatch::orderBy('created_at', 'DESC')
						->get()
						->map(function($job){
							return [
								'name' => $job->name,
								'pending' => ($job->pending_jobs == 0)?'No Pending':'Pending',
								'success' => ($job->pending_jobs == 0 && $job->failed_jobs == 0)?'Success':(($job->pending_jobs > 0 && $job->failed_jobs == 0)?'Not Yet Process':(($job->pending_jobs == 0 && $job->failed_jobs > 0)?'Process with Fail':(($job->pending_jobs > 0 && $job->failed_jobs > 0)?'Process with Fail':NULL))),
								'failed' => ($job->failed_jobs == 0)?'No Failed':'Failed',
								'totalJobs' => $job->total_jobs,
								'processedJobs' => ($job->total_jobs - $job->pending_jobs),
								'created_at' => ($job->created_at->format('j F Y g:i A')),
							];
						});
		return response()->json($values??[]);
	}

	public function getProgress(Request $request): JsonResponse
	{
		try {
			$batchId = $request->id ?? session('lastBatchId');
			$batch1 = Bus::findBatch($batchId);
			// return response()->json([
			// 	'processedJobs' => $batch1->processedJobs(),
			// 	'totalJobs' => $batch1->totalJobs,
			// 	'progress' => $batch1->progress()
			// ]);
			$batch2 = JobBatch::find($batchId);
        // If batch is missing (already deleted), assume finished
			if (!$batch2) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
			$total = $batch2->total_jobs;
			$pending = $batch2->pending_jobs;
			$processed = $total - $pending;
        // Avoid division by zero
			if ($total == 0) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
        // Force return 100 when finished
			if ($pending == 0) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
			// Calculate %
			$percent = number_format((($processed / $total) * 100), 2);
			return response()->json([
																'processedJobs' => $batch1->processedJobs(),
																'totalJobs' => $batch1->totalJobs,
																'progress' => $batch1->progress(),
																'percent' => $percent
															]);
		} catch (\Exception $e) {
			Log::error($e);
			return response()->json([
																'processedJobs' => 0,
																'totalJobs' => 0,
																'progress' => 100,
																'percent' => 100
															]);
		}
	}

	// public function getProgress(Request $request): JsonResponse
	// {
	// 	try {
	// 		$batchId = $request->id ?? session()->get('lastBatchId');

	// 		if (JobBatch::where('id', $batchId)->count()) {
	// 			$resp = JobBatch::where('id', $batchId)->first();

	// 			$total = $resp->total_jobs;
	// 			$pending = $resp->pending_jobs;
	// 			$job_done = $total - $pending;
	// 			$percentbar = number_format((($job_done / $total) * 100), 2);
	// 			return response()->json($percentbar);
	// 		}
	// 	} catch (Exception $e) {
	// 		Log::error($e);
	// 		return response()->json([]);
	// 	}
	// }

	public function getFileEntries(Request $request): JsonResponse
	{
		try {

			$columns = [
				0 => 'id',
				1 => 'file_id',
				2 => 'Year',
				3 => 'Industry_aggregation_NZSIOC',
				4 => 'Industry_code_NZSIOC',
				5 => 'Industry_name_NZSIOC',
				6 => 'Units',
				7 => 'Variable_code',
				8 => 'Variable_name',
				9 => 'Variable_category',
				10 => 'Value',
				11 => 'Industry_code_ANZSIC06',
				12 => 'remarks',
				13 => 'created_at',
				14 => 'updated_at',
				15 => 'deleted_at',
			];

			$query = FileEntry::with('belongstofile')->select([
				'id',
				'file_id',
				'Year',
				'Industry_aggregation_NZSIOC',
				'Industry_code_NZSIOC',
				'Industry_name_NZSIOC',
				'Units',
				'Variable_code',
				'Variable_name',
				'Variable_category',
				'Value',
				'Industry_code_ANZSIC06',
				'remarks',
				'created_at',
				'updated_at',
				'deleted_at',
			]);

			$search = $request->search['value'] ?? null;
			if ($search) {
				$query->with('belongstofile')
					->where(function ($q) use ($search) {
						$q->where('Industry_aggregation_NZSIOC', 'LIKE', "%{$search}%")
						->orWhere('Industry_code_NZSIOC', 'LIKE', "%{$search}%")
						->orWhere('Industry_name_NZSIOC', 'LIKE', "%{$search}%")
						->orWhere('Industry_code_ANZSIC06', 'LIKE', "%{$search}%")
						->orWhereHas('belongstofile', function ($uq) use ($search) {
							$uq->where('file', 'LIKE', "{$search}%");
						});
					});
				}

			$totalRecords = FileEntry::count();
			$filteredRecords = (clone $query)->count();

			$orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
			$orderDir = $request->order[0]['dir'] ?? 'desc';

			$data = $query
			->orderBy($orderColumn, $orderDir)
			->skip($request->start)
			->take($request->length)
			->get();

			return response()->json([
				'draw' => intval($request->draw),
				'recordsTotal' => $totalRecords,
				'recordsFiltered' => $filteredRecords,
				'data' => $data,
			]);

		} catch (Exception $e) {
			Log::error($e);
			return  response()->json(['danger' => $e->getMessage()]);
		}
	}

	public function getSelect2FileEntries(Request $request): JsonResponse
	{
		try {
			$values = FileEntry::select('Industry_code_NZSIOC')
													->when($request->search, function(Builder $query) use ($request){
														$query->where(function ($q) use ($request) {
															$q->where('Industry_name_NZSIOC','LIKE','%'.$request->search.'%')
															->orWhere('Industry_aggregation_NZSIOC','LIKE','%'.$request->search.'%')
															->orWhere('Industry_code_NZSIOC','LIKE','%'.$request->search.'%');
														});
													})
													->when($request->id, function($query) use ($request){
														$query->where('id', $request->id);
													})
													->when($request->idIN, function($query) use ($request){
														$query->whereNotIn('id', $request->idIN);
													})
													->distinct()
													->get();

			return response()->json($values);
		} catch (Exception $e) {
			Log::error($e);
			return  response()->json(['danger' => $e->getMessage()]);
		}
	}

}
