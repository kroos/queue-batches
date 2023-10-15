<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// db relation class to load
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileContent extends Model
{
	use HasFactory;
	protected $table = 'file_contents';
	protected $fillable = [
		'file_id',
		'UNIQUE_KEY',
		'PRODUCT_TITLE',
		'PRODUCT_DESCRIPTION',
		'STYLE#',
		'SANMAR_MAINFRAME_COLOR',
		'SIZE',
		'COLOR_NAME',
		'PIECE_PRICE',
	];


	public function belongstofile(): HasMany
	{
		return $this->BelongsTo(Interview::class, 'file_id');
	}
}
