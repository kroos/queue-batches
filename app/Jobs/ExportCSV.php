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

	protected $hratt;
	/**
	 * Create a new job instance.
	 */
	public function __construct($hratt)
	{
		$this->hratt = $hratt;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$hratt = $this->hratt;

		foreach($hratt as $k1 => $v1){
			// dd($v1);
			$id[$k1] = $v1['id'];
			$file_id[$k1] = File::where('id', $v1['file_id'])->first()->file;
			$Year[$k1] = $v1['Year'];
			$Industry_aggregation_NZSIOC[$k1] = $v1['Industry_aggregation_NZSIOC'];
			$Industry_code_NZSIOC[$k1] = $v1['Industry_code_NZSIOC'];
			$Industry_name_NZSIOC[$k1] = $v1['Industry_name_NZSIOC'];
			$Units[$k1] = $v1['Units'];
			$Variable_code[$k1] = $v1['Variable_code'];
			$Variable_name[$k1] = $v1['Variable_name'];
			$Variable_category[$k1] = $v1['Variable_category'];
			$Value[$k1] = $v1['Value'];
			$Industry_code_ANZSIC06[$k1] = $v1['Industry_code_ANZSIC06'];
			$remarks[$k1] = $v1['remarks'];
			$created_at[$k1] = ($v1['created_at'])?\Carbon\Carbon::parse($v1['created_at'])->format('j M Y g:H a'):NULL;
			$updated_at[$k1] = ($v1['updated_at'])?\Carbon\Carbon::parse($v1['updated_at'])->format('j M Y g:H a'):NULL;
			$deleted_at[$k1] = ($v1['deleted_at'])?\Carbon\Carbon::parse($v1['deleted_at'])->format('j M Y g:H a'):NULL;

			$records[$k1] = [
												$id[$k1], $file_id[$k1], $Year[$k1], $Industry_aggregation_NZSIOC[$k1], $Industry_code_NZSIOC[$k1], $Industry_name_NZSIOC[$k1], $Units[$k1], $Variable_code[$k1], $Variable_name[$k1], $Variable_category[$k1], $Value[$k1], $Industry_code_ANZSIC06[$k1], $remarks[$k1], $created_at[$k1], $updated_at[$k1], $deleted_at[$k1]
											];
		}
		$filePath = 'app/private/csv/generate.csv';
		$handle1 = fopen(storage_path($filePath), 'a+');
		foreach ($records as $value) {
			fputcsv($handle1, $value);
		}
		fclose($handle1);
	}
}
