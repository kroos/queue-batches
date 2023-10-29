<?php

namespace App\Jobs;

// load laravel excel
// use App\Imports\CSVFileImport;
// use Maatwebsite\Excel\Facades\Excel;

// load model
use App\Models\FileContent;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

// load helper
use Illuminate\Support\Arr;

class ProcessCSV implements ShouldQueue
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
		FileContent::upsert($this->datacsv,
								['UNIQUE_KEY'],
								['file_id', 'PRODUCT_TITLE', 'PRODUCT_DESCRIPTION', 'STYLE#', 'SANMAR_MAINFRAME_COLOR', 'SIZE', 'COLOR_NAME', 'PIECE_PRICE']
							);
	}
}
