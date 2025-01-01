<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\GoldPrice;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\Rule;

class ProductController extends Controller {
	use ApiResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', Product::class);
		$productQuery = QueryBuilder::for( Product::class)
			->allowedIncludes( [ 'category', 'producer', 'trader', 'shop', 'invoice', 'goldPrice',] )
			->defaultSort( '-created_at' )
			->allowedSorts( 'name', 'price', 'created_at' )
			->allowedFilters( [ 'name', 'code' ] );
		if ( request( 'categoryId' ) ) {
			$productQuery->where( 'category_id', request( 'categoryId' ) );
		}
		if ( request( 'producerId' ) ) {
			$productQuery->where( 'producer_id', request( 'producerId' ) );
		}
		if ( request( 'traderId' ) ) {
			$productQuery->where( 'trader_id', request( 'traderId' ) );
		}
		if ( request( 'shopId' ) ) {
			$productQuery->where( 'shop_id', request( 'shopId' ) );
		}
		if ( request( 'invoiceId' ) ) {
			$productQuery->where( 'invoice_id', request( 'invoiceId' ) );
		}
		if ( request( 'goldPriceId' ) ) {
			$productQuery->where( 'gold_price_id', request( 'goldPriceId' ) );
		}
		if ( request( 'minWeight' ) ) {
			$productQuery->where( 'weight', '>=', request( 'minWeight' ) );
		}
		if ( request( 'maxWeight' ) ) {
			$productQuery->where( 'weight', '<=', request( 'maxWeight' ) );
		}
		if ( request( 'code' ) ) {
			$productQuery->where( 'code', '=', request( 'code' ) );
		}
		if ( request( 'sold' ) ) {
			$productQuery->where( 'sold', request( 'sold' ) );
		}

		$products = $productQuery->paginate( request()->get( 'limit' ) ?? 10 );

		return $this->success(
			ProductResource::collection( $products ),
			pagination: true,
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', Product::class);
		$validated = $request->validate( [ 
			'category_id' => 'required|exists:categories,id',
			'producer_id' => 'required|exists:producers,id',
			'trader_id' => 'required|exists:traders,id',
			'shop_id' => 'required|exists:shops,id',
			// 'invoice_id' => 'sometimes|exists:invoices,id',
			'gold_price_id' => 'required|exists:gold_prices,id',
			'name' => [ 
				'sometimes',
				// Rule::unique( 'products', 'name' ),
				'min:3',
				'max:255'
			],
			'code' => [ 
				'required',
				Rule::unique( 'products', 'code' ),
				'max:255'
			],
			'price' => 'sometimes|numeric',
			'weight' => [ 'required', 'numeric' ],
			'sold' => 'required|boolean',
			'manufacture_cost' => [ 'sometimes', 'numeric' ],

		] );

		$product = Product::create( $validated );
		$traderGoldBalance = $product->trader->gold_balance;
		$product->trader->update( [ 
			'gold_balance' => $traderGoldBalance + $product->weight,
		] );
		return $this->success( new ProductResource( $product ) );//
	}

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$product = QueryBuilder::for( Product::class)
			->allowedIncludes( [ 'category', 'producer', 'trader', 'shop', 'invoice', 'goldPrice',] )
			->where( 'id', $id )
			->first();
		if ( ! $product ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'view', $product );
		$goldPrice = GoldPrice::where( 'standard', $product->standard )->first();
		return $this->success(
			new ProductResource( $product ),
			additionalData: [ 'gramePrice' => $goldPrice->price ]
		);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Product $product ) {
		Gate::authorize( 'update', $product );
		$validated = $request->validate( [ 
			'category_id' => 'sometimes|exists:categories,id',
			'producer_id' => 'sometimes|exists:producers,id',
			'trader_id' => 'sometimes|exists:traders,id',
			'shop_id' => 'sometimes|exists:shops,id',
			// 'invoice_id' => 'sometimes|exists:invoices,id',
			'gold_price_id' => 'sometimes|exists:gold_prices,id',
			'name' => [ 
				'sometimes',
				// Rule::unique( 'products', 'name' )->ignore( $product->id ),
				'min:3',
				'max:255'
			],
			'code' => [ 
				'sometimes',
				Rule::unique( 'products', 'code' )->ignore( $product->id ),
				'max:255'
			],
			'price' => 'sometimes|numeric',
			'weight' => [ 'sometimes', 'numeric' ],
			'sold' => 'sometimes|boolean',
			'manufacture_cost' => [ 'sometimes', 'numeric' ],

		] );
		$product->update( $validated );
		return $this->success( new ProductResource( $product ) );//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Product $product ) {
		Gate::authorize( 'delete', $product );
		$product->delete();
		return $this->success( [], message: 'تم حذف المنتج بنجاح' );
	}
}
