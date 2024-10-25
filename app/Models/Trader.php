<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trader extends Model {
	use HasFactory;
	use SoftDeletes;

	protected $fillable = [ 
		'name',
		'phone',
		'address',
		'money_balance',
		'gold_balance',
	];

	//! Relationships
	public function products(): HasMany {
		return $this->hasMany( Product::class);
	}
}
