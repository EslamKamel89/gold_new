<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model {
	use SoftDeletes;

	protected $fillable = [ 
		'name',
	];
	//! Relationships
	public function invoices(): HasMany {
		return $this->hasMany( Invoice::class);
	}

	public function updateInvoices(): HasMany {
		return $this->hasMany( User::class, 'update_shop_id' );
	}
	public function products(): HasMany {
		return $this->hasMany( Product::class);
	}
}
