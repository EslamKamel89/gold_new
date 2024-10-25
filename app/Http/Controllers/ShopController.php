<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ShopController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', Shop::class);
		return $this->success(
			ShopResource::collection( Shop::paginate( request()->get( 'limit' ) ?? 10 ) ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		//
		Gate::authorize( 'create', Shop::class);
		$validated = $request->validate( [ 
			'name' => 'required|unique:shops,name|min:3|max:255'
		] );
		$shop = Shop::create( $validated );
		return $this->success( new ShopResource( $shop ) );
	}

	/**
	 * Display the specified resource.
	 */
	public function show( Shop $shop ) {
		//
		Gate::authorize( 'view', $shop );
		return $this->success( new ShopResource( $shop ) );
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Shop $shop ) {
		//
		Gate::authorize( 'update', $shop );
		$validated = $request->validate( [ 
			'name' => [ 'required', Rule::unique( 'shops', 'name' )->ignore( $shop->id, 'id' ), 'min:3', 'max:255' ]
		] );
		$shop->update( $validated );
		return $this->success( new ShopResource( $shop ) );
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Shop $shop ) {
		//
		Gate::authorize( 'delete', $shop );
		$shop->delete();
		return $this->success( [], message: 'تم حذف المحل بنجاح' );
	}
}
