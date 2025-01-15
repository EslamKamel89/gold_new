<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrokenResource;
use App\Models\Broken;
use Illuminate\Http\Request;
use App\Http\Resources\TraderResource;
use App\Models\Trader;
use App\Http\Requests\StoreTraderRequest;
use App\Http\Requests\UpdateTraderRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrokenController extends Controller {
	use ApiResponse;
	public function index() {

		$brokens = Broken::paginate( request()->get( 'limit' ) ?? 10 );

		return $this->success(
			BrokenResource::collection( $brokens ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {

		$validated = $request->validate( [ 
			'seller' => 'sometimes|max:255',
			'weight' => 'sometimes|numeric',
			'price' => 'sometimes|numeric',
			'standard' => 'sometimes|max:255',
			'type' => 'sometimes|max:255',

		] );
		$broken = Broken::create( $validated );
		return $this->success( new BrokenResource( $broken ) );//
	}

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$broken = Broken::find( $id );

		if ( ! $broken ) {
			throw new NotFoundHttpException();
		}

		return $this->success(
			new BrokenResource( $broken ),
		);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Broken $broken ) {

		$validated = $request->validate( [ 
			'seller' => 'sometimes|max:255',
			'weight' => 'sometimes|numeric',
			'price' => 'sometimes|numeric',
			'standard' => 'sometimes|max:255',
			'type' => 'sometimes|max:255',
		] );
		$broken->update( $validated );
		return $this->success( new BrokenResource( $broken ) );//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Broken $broken ) {

		$broken->delete();
		return $this->success( [], message: 'تم حذف الدائن بنجاح' );
	}
}
