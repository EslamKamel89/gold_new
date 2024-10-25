<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoldPriceResource;
use App\Models\GoldPrice;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class GoldPriceController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', GoldPrice::class);
		$goldPriceQuery = QueryBuilder::for( GoldPrice::class)
			->defaultSort( '-price' )
			->allowedSorts( 'price' )
			->allowedFilters( [ 'standard' ] );


		$goldPrices = $goldPriceQuery->paginate( request()->get( 'limit' ) ?? 10 );

		return $this->success(
			GoldPriceResource::collection( $goldPrices ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', GoldPrice::class);
		$validated = $request->validate( [ 
			'standard' => 'required|unique:gold_prices,standard|min:3|max:255',
			'description' => 'sometimes|min:3|max:255',
			'price' => 'required|numeric',
		] );
		$goldPrice = GoldPrice::create( $validated );
		return $this->success( new GoldPriceResource( $goldPrice ) );//
	}

	/**
	 * Display the specified resource.
	 */
	public function show( GoldPrice $goldPrice ) {
		Gate::authorize( 'view', $goldPrice );
		return $this->success(
			new GoldPriceResource( $goldPrice ),
		);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, GoldPrice $goldPrice ) {
		Gate::authorize( 'update', $goldPrice );
		$validated = $request->validate( [ 
			'standard' => [ 
				'sometimes',
				Rule::unique( 'gold_prices', 'standard' )->ignore( $goldPrice->id ),
				'min:3',
				'max:255'
			],
			'description' => 'sometimes|min:3|max:255',
			'price' => 'sometimes|numeric',
		] );
		$goldPrice->update( $validated );
		return $this->success( new GoldPriceResource( $goldPrice ) );//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( GoldPrice $goldPrice ) {
		Gate::authorize( 'delete', $goldPrice );
		$goldPrice->delete();
		return $this->success( [], message: 'تم حذف السعر بنجاح' );
	}
}
