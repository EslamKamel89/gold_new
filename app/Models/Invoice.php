<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
	use SoftDeletes;

	use HasFactory;
	protected $fillable = [ 
		'user_id',
		'shop_id',
		'total_price',
		'manufacture_cost_gram_18',
		'manufacture_cost_gram_21',
		'manufacture_cost_gram_24',
		'quantity',
		'customer_name',
		'customer_phone',
		'update_user_id',
		'update_shop_id',
	];

	//!Relationships
	public function orderProducts(): BelongsToMany {
		return $this->belongsToMany(
			related: Product::class,
			table: 'orders',
			foreignPivotKey: 'invoice_id',
			relatedPivotKey: 'product_id',
		)->withPivot( 'description', 'unit_price' )
			->as( 'orders' )
			->withTimestamps();
	}
	public function invoiceCreator(): BelongsTo {
		return $this->belongsTo( User::class, 'user_id' );
	}
	public function invoiceUpdater(): BelongsTo {
		return $this->belongsTo( User::class, 'update_user_id' );
	}

	public function createdInShop(): BelongsTo {
		return $this->belongsTo( Shop::class, 'shop_id' );
	}

	public function updatedInShop(): BelongsTo {
		return $this->belongsTo( Shop::class, 'update_shop_id' );
	}
	public function products(): HasMany {
		return $this->hasMany( Product::class, );
	}
}
