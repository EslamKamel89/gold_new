<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldPrice extends Model {
	use HasFactory;
	use SoftDeletes;
	protected $fillable = [ 
		'standard',
		'description',
		'price',
	];
	public function products(): HasMany {
		return $this->hasMany( Product::class);
	}
}
