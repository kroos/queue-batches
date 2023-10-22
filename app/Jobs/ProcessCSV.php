<?php

namespace App\Jobs;

// load model
// use App\Models\FileContent;

// load excel
use App\Imports\CSVFileImport;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCSV implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 */
	public function __construct(CSVFileImport $csvfileimport)
	{
		$this->csvfileimport = $csvfileimport;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{

	}
}
