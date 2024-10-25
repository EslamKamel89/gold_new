<?php

namespace App\Http\Controllers;

use App\Http\Resources\TraderResource;
use App\Models\Trader;
use App\Http\Requests\StoreTraderRequest;
use App\Http\Requests\UpdateTraderRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TraderController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 * allowed includes >> items
	 * defaultSort -money_balance
	 * allowedSorts money_balance , gold_balance
	 * allowedFilters name,phone,address
	 */
	public function index() {
		Gate::authorize( 'viewAny', Trader::class);
		$traderQuery = QueryBuilder::for( Trader::class)
			->allowedIncludes( [ 'products' ] )
			->defaultSort( '-money_balance' )
			->allowedSorts( 'money_balance', 'gold_balance' )
			->allowedFilters( [ 'name', 'phone', 'address' ] );


		$traders = $traderQuery->paginate( request()->get( 'limit' ) ?? 10 );

		return $this->success(
			TraderResource::collection( $traders ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', Trader::class);
		$validated = $request->validate( [ 
			'name' => 'required|unique:traders,name',
			'phone' => 'required|unique:traders,phone',
			'address' => 'sometimes|min:3|max:255',
			'money_balance' => 'required|numeric',
			'gold_balance' => 'required|numeric',
		] );
		$trader = Trader::create( $validated );
		return $this->success( new TraderResource( $trader ) );//
	}

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$trader = QueryBuilder::for( Trader::class)
			->allowedIncludes( [ 'products' ] )
			->where( 'id', $id )
			->first();
		if ( ! $trader ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'view', $trader );
		return $this->success(
			new TraderResource( $trader ),
		);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Trader $trader ) {
		Gate::authorize( 'update', $trader );
		$validated = $request->validate( [ 
			'name' => [ 
				'sometimes',
				Rule::unique( 'traders', 'name' )->ignore( $trader->id ),
				'min:3',
				'max:255'
			],
			'phone' => [ 
				'sometimes',
				Rule::unique( 'traders', 'phone' )->ignore( $trader->id ),
				'min:3',
				'max:255'
			],
			'address' => 'sometimes|min:3|max:255',
			'money_balance' => 'sometimes|numeric',
			'gold_balance' => 'sometimes|numeric',
		] );
		$trader->update( $validated );
		return $this->success( new TraderResource( $trader ) );//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Trader $trader ) {
		Gate::authorize( 'delete', $trader );
		$trader->delete();
		return $this->success( [], message: 'تم حذف الدائن بنجاح' );
	}
}
