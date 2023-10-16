<?php

namespace App\Imports;

// load model
use App\Models\FileContent;

// load utf8 encoding
use \App\Helpers\Encoding;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CSVFileImport implements ToModel, WithUpserts, WithUpsertColumns, WithBatchInserts, WithChunkReading*//*, WithProgressBar, WithHeadingRow, WithCustomCsvSettings
// class CSVFileImport implements ToCollection/*, WithHeadingRow*/
{
	use RemembersRowNumber, Importable;

	public function model(array $row)
	// public function collection(Collection $rows)
	{
		// Remembering row numbers is only intended for ToModel imports.
		$currentRowNumber = $this->getRowNumber();

		HeadingRowFormatter::default('none');

		return new FileContent([
			// 'unique_key' => Encoding::fixUTF8($row[0]),
			// 'product_title' => Encoding::fixUTF8($row[1]),
			// 'product_description' => Encoding::fixUTF8($row[2]),
			// 'style' => Encoding::fixUTF8($row[3]),
			// 'sanmar_mainframe_color' => Encoding::fixUTF8($row[28]),
			// 'size' => Encoding::fixUTF8($row[18]),
			// 'color_name' => Encoding::fixUTF8($row[14]),
			// 'piece_price' => Encoding::fixUTF8($row[21]),
			// 'UNIQUE_KEY' => Encoding::fixUTF8($row[0]),
			// 'PRODUCT_TITLE' => Encoding::fixUTF8($row[1]),
			// 'PRODUCT_DESCRIPTION' => Encoding::fixUTF8($row[2]),
			// 'STYLE#' => Encoding::fixUTF8($row[3]),
			// 'SANMAR_MAINFRAME_COLOR' => Encoding::fixUTF8($row[28]),
			// 'SIZE' => Encoding::fixUTF8($row[18]),
			// 'COLOR_NAME' => Encoding::fixUTF8($row[14]),
			// 'PIECE_PRICE' => Encoding::fixUTF8($row[21]),
			'UNIQUE_KEY' => Encoding::fixUTF8($row['unique_key']),
			'PRODUCT_TITLE' => Encoding::fixUTF8($row['product_title']),
			'PRODUCT_DESCRIPTION' => Encoding::fixUTF8($row['product_description']),
			'STYLE#' => Encoding::fixUTF8($row['style']),
			'SANMAR_MAINFRAME_COLOR' => Encoding::fixUTF8($row['sanmar_mainframe_color']),
			'SIZE' => Encoding::fixUTF8($row['size']),
			'COLOR_NAME' => Encoding::fixUTF8($row['color_name']),
			'PIECE_PRICE' => Encoding::fixUTF8($row['piece_price']),
		]);

		// for collection
		// foreach ($rows as $row) {
		// }
	}

	// the 2nd row will now be used as heading row
	// public function headingRow(): int
	// {
	// 	return 2;
	// }

	public function batchSize(): int
	{
		return 200;
	}

	public function chunkSize(): int
	{
		return 1000;
	}

	public function uniqueBy()
	{
		return 'UNIQUE_KEY';
	}

	// if a user already exists, only "name" and "role" columns will be updated.
	public function upsertColumns()
	{
		// return ['name', 'role'];
		return [
					'PRODUCT_TITLE',
					'PRODUCT_DESCRIPTION',
					'STYLE#',
					'SANMAR_MAINFRAME_COLOR',
					'SIZE',
					'COLOR_NAME',
					'PIECE_PRICE',
		];
	}

	public function getCsvSettings(): array
	{
		return [
			'input_encoding' => 'UTF-8'
		];
	}

}
