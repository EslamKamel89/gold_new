<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
	use SoftDeletes;

	use HasFactory;
	protected $table = 'orders';
	protected $fillable = [ 
		'product_id',
		'invoice_id',
		'description',
		"unit_price",
		"returned"
	];

	//! casts
	protected $casts = [ 
		'returned' => 'boolean',
	];

	//! Relatioships
	public function product(): BelongsTo {
		return $this->belongsTo( Product::class);
	}
	public function invoice(): BelongsTo {
		return $this->belongsTo( Invoice::class);
	}


}
