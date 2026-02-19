<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

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
	// this 1 need chunks sooner or later

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




	public function getYesNoOptions(Request $request): JsonResponse
	{
		$yno = YesNoOption::when($request->search, function (Builder $query) use ($request) {
						$query->where('option', 'LIKE', '%' . $request->search . '%');
					})
					->get();
		return response()->json($yno);
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
			$values = FileEntry::with('belongstofile')
											->when($request->search, function(Builder $query) use ($request){
												$query->where('Industry_name_NZSIOC','LIKE','%'.$request->search.'%')
												->orWhere('Industry_aggregation_NZSIOC','LIKE','%'.$request->search.'%')
												->orWhere('Industry_code_NZSIOC','LIKE','%'.$request->search.'%');
											})
											->when($request->id, function($query) use ($request){
												$query->where('id', $request->id);
											})
											->when($request->idIN, function($query) use ($request){
												$query->whereNotIn('id', $request->idIN);
											})
											->orderBy('Industry_code_NZSIOC')
											->get();
			return response()->json($values);
		} catch (Exception $e) {
			Log::error($e);
			return  response()->json([]);
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
			return  response()->json([]);
		}
	}

}
