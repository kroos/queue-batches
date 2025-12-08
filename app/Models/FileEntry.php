<?php
namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use App\Models\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\HasOneThrough;
// use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// load helper
use Illuminate\Support\Str;

class FileEntry extends Model
{
	//
	use SoftDeletes;
	// protected $connection = '';
	protected $table = 'file_entries';
	// protected $primaryKey = '';
	// public $incrementing = false;
	// protected $keyType = '';
	// const CREATED_AT = '';
	// const UPDATED_AT = '';
	// protected $rememberTokenName = '';

	protected $casts = [
		// 'Year' => 'date',
	];

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	// set column attribute
	public function setIndustry_aggregation_NZSIOCAttribute($value)
	{
	    $this->attributes['Industry_aggregation_NZSIOC'] = ucfirst(Str::lower($value));
	}

	public function setIndustry_code_NZSIOCAttribute($value)
	{
	    $this->attributes['Industry_code_NZSIOC'] = ucfirst(Str::lower($value));
	}

	public function setIndustry_name_NZSIOCAttribute($value)
	{
	    $this->attributes['Industry_name_NZSIOC'] = ucfirst(Str::lower($value));
	}

	public function setUnitsAttribute($value)
	{
	    $this->attributes['Units'] = ucfirst(Str::lower($value));
	}

	public function setVariable_codeAttribute($value)
	{
	    $this->attributes['Variable_code'] = ucfirst(Str::lower($value));
	}

	public function setVariable_nameAttribute($value)
	{
	    $this->attributes['Variable_name'] = ucfirst(Str::lower($value));
	}

	public function setVariable_categoryAttribute($value)
	{
	    $this->attributes['Variable_category'] = ucfirst(Str::lower($value));
	}

	public function setValueAttribute($value)
	{
	    $this->attributes['Value'] = ucfirst(Str::lower($value));
	}

	public function setIndustry_code_ANZSIC06Attribute($value)
	{
	    $this->attributes['Industry_code_ANZSIC06'] = ucfirst(Str::lower($value));
	}

	public function setremarksAttribute($value)
	{
	    $this->attributes['remarks'] = Str::lower($value);
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	// relationship
	public function belongstofile(): BelongsTo
	{
		return $this->BelongsTo(\App\Models\File::class, 'file_id');
	}
}
