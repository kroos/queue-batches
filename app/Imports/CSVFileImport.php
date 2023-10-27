<?php

namespace App\Imports;

// load model
use App\Models\FileContent;

// load utf8 encoding
use \App\Helpers\Encoding;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CSVFileImport implements ToModel, WithStartRow, WithChunkReading, WithUpserts, WithUpsertColumns, WithBatchInserts, WithCustomCsvSettings
{
	use Importable;

	public function model(array $row)
	{
		return new FileContent([
			'UNIQUE_KEY' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0])),
			'PRODUCT_TITLE' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[1])),
			'PRODUCT_DESCRIPTION' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[2])),
			'STYLE' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[3])),
			'SANMAR_MAINFRAME_COLOR' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[28])),
			'SIZE' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[18])),
			'COLOR_NAME' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[14])),
			'PIECE_PRICE' => Encoding::fixUTF8(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[21])),
		]);
	}

	public function startRow(): int
	{
		return 2;
	}

	public function batchSize(): int
	{
		return 1000;
	}

	public function chunkSize(): int
	{
		return 1000;
	}

	public function uniqueBy()
	{
		return 'UNIQUE_KEY';
		// must set unique at column of the table
	}

	// if a user already exists, only "name" and "role" columns will be updated.
	public function upsertColumns()
	{
		return [
				// 'UNIQUE_KEY',
				'PRODUCT_TITLE',
				'PRODUCT_DESCRIPTION',
				'STYLE',
				'SANMAR_MAINFRAME_COLOR',
				'SIZE',
				'COLOR_NAME',
				'PIECE_PRICE',
		];
	}

	public function getCsvSettings(): array
	{
		return [
			'input_encoding' => 'UTF-8',
			'output_encoding' => 'UTF-8',
		];
	}

}
