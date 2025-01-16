<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Broken extends Model {
	/** @use HasFactory<\Database\Factories\BrokenFactory> */
	use HasFactory;
	protected $fillable = [ 
		'category_id',
		'price',
		'standard',
		'type',
		'seller',
		'weight',
	];
	public function category(): BelongsTo {
		return $this->belongsTo( Category::class);
	}
}
