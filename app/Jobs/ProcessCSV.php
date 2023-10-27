<?php

namespace App\Jobs;

// load model
use App\Models\FileContent;

// load excel
use App\Imports\CSVFileImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class ProcessCSV implements ShouldQueue
{
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $lfile;

	/**
	 * Create a new job instance.
	 */
	public function __construct($lfile)
	{
		$this->lfile = $lfile;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		Excel::import(new CSVFileImport, $this->lfile);
	}
}
