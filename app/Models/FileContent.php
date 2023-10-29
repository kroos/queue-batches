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
	protected $guarded = [];

	public function belongstointerview(): BelongsTo
	{
		return $this->belongsTo(Interview::class, 'file_id');
	}
}
