<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', Category::class);
		$categories = QueryBuilder::for( Category::class)
			->allowedFilters( [ 'name' ] )->paginate( request()->get( 'limit' ) ?? 10 );
		return $this->success(
			CategoryResource::collection( $categories ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', Category::class);
		$validated = $request->validate( [ 
			'name' => 'required|unique:categories,name|min:3|max:255'
		] );
		$category = Category::create( $validated );
		return $this->success( new CategoryResource( $category ) );
	}

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$category = QueryBuilder::for( Category::class)
			->allowedIncludes( [ 'products' ] )
			->where( 'id', $id )
			->first();
		if ( ! $category ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'view', $category );
		return $this->success( new CategoryResource( $category ) );
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Category $category ) {
		Gate::authorize( 'update', $category );
		$validated = $request->validate( [ 
			'name' => [ 'required', Rule::unique( 'categories', 'name' )->ignore( $category->id, 'id' ), 'min:3', 'max:255' ]
		] );
		$category->update( $validated );
		return $this->success( new CategoryResource( $category ) );
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Category $category ) {
		Gate::authorize( 'delete', $category );
		$category->delete();
		return $this->success( [], message: 'تم حذف الصنف بنجاح' );
	}
}
