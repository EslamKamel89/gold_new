<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProducerResource;
use App\Models\Producer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProducerController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', Producer::class);
		$producers = QueryBuilder::for( Producer::class)
			->allowedFilters( [ 'name' ] )
			->paginate( request()->get( 'limit' ) ?? 10 );
		return $this->success(
			ProducerResource::collection( $producers ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', Producer::class);
		$validated = $request->validate( [ 
			'name' => 'required|unique:producers,name|min:3|max:255'
		] );
		$producer = Producer::create( $validated );
		return $this->success( new ProducerResource( $producer ) );//
	}

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$producer = QueryBuilder::for( producer::class)
			->allowedIncludes( [ 'products' ] )
			->where( 'id', $id )
			->first();
		if ( ! $producer ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'view', $producer );
		return $this->success( new producerResource( $producer ) );//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Producer $producer ) {
		Gate::authorize( 'update', $producer );
		$validated = $request->validate( [ 
			'name' => [ 'required', Rule::unique( 'producers', 'name' )->ignore( $producer->id, 'id' ), 'min:3', 'max:255' ]
		] );
		$producer->update( $validated );
		return $this->success( new ProducerResource( $producer ) );
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Producer $producer ) {
		Gate::authorize( 'delete', $producer );
		$producer->delete();
		return $this->success( [], message: 'تم حذف الصانع بنجاح' );
	}
}
