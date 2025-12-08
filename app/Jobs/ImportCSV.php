<?php

namespace App\Jobs;

// load laravel excel
// use App\Imports\CSVFileImport;
// use Maatwebsite\Excel\Facades\Excel;

// load model
use App\Models\FileEntry;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

// load helper
use Illuminate\Support\Arr;

class ImportCSV implements ShouldQueue
{
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $datacsv;

	/**
	 * Create a new job instance.
	 */
	public function __construct($datacsv)
	{
		$this->datacsv = $datacsv;
		// dd($this->datacsv);
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		FileEntry::insert($this->datacsv);
		// foreach ($this->datacsv as $row) {
		// 	FileEntry::create($row);
		// }
		// FileEntry::upsert($this->datacsv,
		// 						['Variable_code'],
		// 						[
		// 							'Year',
		// 							'Industry_aggregation_NZSIOC',
		// 							'Industry_code_NZSIOC',
		// 							'Industry_name_NZSIOC',
		// 							'Units',
		// 							'Variable_name',
		// 							'Variable_category',
		// 							'Value',
		// 							'Industry_code_ANZSIC06',
		// 							'file_id'
		// 						]
		// 					);
	}
}
