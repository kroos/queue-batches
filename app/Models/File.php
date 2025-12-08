<?php
namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use App\Models\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\HasOneThrough;
// use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// load helper
use Illuminate\Support\Str;

class File extends Model
{
	//
	use SoftDeletes;
	// protected $connection = '';
	protected $table = 'files';
	// protected $primaryKey = '';
	// public $incrementing = false;
	// protected $keyType = '';
	// const CREATED_AT = '';
	// const UPDATED_AT = '';
	// protected $rememberTokenName = '';

	// protected $casts = [
	// 	'is_active' => 'boolean',
	// ];

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	// set column attribute
	public function setFileAttribute($value)
	{
	    $this->attributes['file'] = Str::lower($value);
	}

	public function setFileOriginalAttribute($value)
	{
	    $this->attributes['file_original'] = Str::lower($value);
	}

	public function setRemarksAttribute($value)
	{
	    $this->attributes['remarks'] = Str::lower($value);
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	// relationship
	public function hasmanyfileentries(): HasMany
	{
		return $this->hasMany(\App\Models\FileEntry::class, 'file_id');
	}
}
