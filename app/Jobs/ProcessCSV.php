<?php

namespace App\Jobs;

// load model
use App\Models\FileContent;

// load excel
// use App\Imports\CSVFileImport;

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

	public $mydata;

	/**
	 * Create a new job instance.
	 */
	public function __construct($mydata)
	{
		$this->mydata = $mydata;
		// dd($this->mydata);
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		// need to use upsert
		foreach ($this->mydata as $mydata) {
			$my = new FileContent();
			$my->UNIQUE_KEY = $mydata['UNIQUE_KEY'];
			$my->PRODUCT_TITLE = $mydata['PRODUCT_TITLE'];
			$my->PRODUCT_DESCRIPTION = $mydata['PRODUCT_DESCRIPTION'];
			$my->STYLE = $mydata['STYLE#'];
			$my->SANMAR_MAINFRAME_COLOR = $mydata['SANMAR_MAINFRAME_COLOR'];
			$my->SIZE = $mydata['SIZE'];
			$my->COLOR_NAME = $mydata['COLOR_NAME'];
			$my->PIECE_PRICE = $mydata['PIECE_PRICE'];
			$my->save();
		}
	}
}
