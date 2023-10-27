<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// db relation class to load
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
	use HasFactory;
	protected $table = 'files';
	protected $guarded = [];
	// protected $fillable = [
	// 	'file', 'status',
	// ];

	public function hasmanycontents(): HasMany
	{
		return $this->hasMany(FileContent::class, 'file_id');
	}
}
