<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
	use SoftDeletes;

	use HasFactory;
	protected $fillable = [ 
		"category_id",
		"producer_id",
		"trader_id",
		"shop_id",
		"invoice_id",
		"gold_price_id",
		"code",
		"name",
		"price",
		"weight",
		"manufacture_cost",
		"sold",
	];

	protected function casts(): array {
		return [ 
			"sold" => "boolean",
		];
	}

	//! Relationships
	public function category(): BelongsTo {
		return $this->belongsTo( Category::class);
	}
	public function producer(): BelongsTo {
		return $this->belongsTo( Producer::class);
	}
	public function trader(): BelongsTo {
		return $this->belongsTo( Trader::class);
	}
	public function shop(): BelongsTo {
		return $this->belongsTo( Shop::class);
	}
	public function invoice(): BelongsTo {
		return $this->belongsTo( Invoice::class);
	}
	public function goldPrice(): BelongsTo {
		return $this->belongsTo( GoldPrice::class);
	}

	public function orderInvoices(): BelongsToMany {
		return $this->belongsToMany(
			related: Invoice::class,
			table: "orders",
			foreignPivotKey: "product_id",
			relatedPivotKey: "invoice_id",
		)->withPivot( "description", "unit_price" )
			->as( "orders" )
			->withTimestamps();
	}
}
