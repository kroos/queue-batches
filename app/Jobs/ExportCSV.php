<?php
namespace App\Jobs;

// load laravel excel
// use App\Imports\CSVFileImport;
// use Maatwebsite\Excel\Facades\Excel;

// load model
use App\Models\FileEntry;
use App\Models\File;

use Illuminate\Support\Facades\Storage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

// load helper
use Illuminate\Support\Arr;

class ExportCSV implements ShouldQueue
{
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 */
	protected array $ids;
	public function __construct(array $ids)
	{
		$this->ids = $ids;
	}

	/**
	 * Execute the job. */
	public function handle(): void
	{
		$rows = FileEntry::whereIn('id', $this->ids)->get();

		$filePath = storage_path('app/private/csv/generate.csv');
		$handle = fopen($filePath, 'a+');

		foreach ($rows as $row) {

			$record = [
				$row->id,
				File::where('id', $row->file_id)->first()->file,
				$row->Year,
				$row->Industry_aggregation_NZSIOC,
				$row->Industry_code_NZSIOC,
				$row->Industry_name_NZSIOC,
				$row->Units,
				$row->Variable_code,
				$row->Variable_name,
				$row->Variable_category,
				$row->Value,
				$row->Industry_code_ANZSIC06,
				$row->remarks,
				optional($row->created_at)?->format('j M Y g:i a'),
				optional($row->updated_at)?->format('j M Y g:i a'),
				optional($row->deleted_at)?->format('j M Y g:i a'),
			];

			fputcsv($handle, $record);
		}

		fclose($handle);
	}
}
