<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broken extends Model {
	/** @use HasFactory<\Database\Factories\BrokenFactory> */
	use HasFactory;
	protected $fillable = [ 
		'price',
		'standard',
		'type',
		'seller',
		'weight',
	];
}
