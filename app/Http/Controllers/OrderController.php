<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\OrderResource;
use App\Models\Invoice;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller {
	use ApiResponse;

	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		Gate::authorize( 'viewAny', Order::class);
		$orderQuery = QueryBuilder::for( Order::class)
			->allowedIncludes( [ 'product', 'invoice',] )
			->defaultSort( '-created_at' )
			->allowedSorts( 'unit_price', 'created_at' )
			->withTrashed();
		if ( request( 'productId' ) ) {
			$orderQuery->where( 'product_id', request( 'productId' ) );
		}
		if ( request( 'invoiceId' ) ) {
			$orderQuery->where( 'invoice_id', request( 'invoiceId' ) );
		}
		if ( request( 'minPrice' ) ) {
			$orderQuery->where( 'unit_price', '>=', request( 'minPrice' ) );
		}
		if ( request( 'maxPrice' ) ) {
			$orderQuery->where( 'unit_price', '<=', request( 'maxPrice' ) );
		}
		if ( request( 'udpdatedOrDeleted' ) == true ) {
			$orderQuery->whereNotNull( 'deleted_at' );
		}
		if ( request( 'updated' ) == true ) {
			$orderQuery->whereNotNull( 'deleted_at' )->where( 'returned', false );
		}
		if ( request( 'deleted' ) == true ) {
			$orderQuery->whereNotNull( 'deleted_at' )->where( 'returned', true );
		}

		$orders = $orderQuery->paginate( request()->get( 'limit' ) ?? 10 );

		return $this->success(
			OrderResource::collection( $orders ),
			pagination: true,
		);
	}



	/**
	 * Store a newly created resource in storage.
	 */
	// public function store( StoreOrderRequest $request ) {
	// }

	/**
	 * Display the specified resource.
	 */
	public function show( int $id ) {
		$order = QueryBuilder::for( Order::class)
			->allowedIncludes( [ 'product', 'invoice',] )
			->where( 'id', $id )
			->withTrashed()
			->first();
		if ( ! $order ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'view', $order );
		return $this->success(
			new OrderResource( $order ),
		);
	}



	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, int $id ) {
		$order = Order::withTrashed()->find( $id );
		if ( ! $order ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'update', $order );
		if ( $order->returned ) {
			return $this->failure( 'You are trying to update an order that was previously returned' );
		}
		if ( $order->deleted_at ) {
			return $this->failure( 'You are trying to update an order that was previously updated' );
		}

		$validated = $request->validate( [ 
			'unit_price' => 'required|numeric',
			'shop_id' => 'required|exists:shops,id'
		] );
		$newOrder = Order::create( [ 
			'product_id' => $order->product_id,
			'invoice_id' => $order->invoice_id,
			'description' => $order->description,
			"unit_price" => $validated['unit_price'],
		] );
		$order->invoice->update( [ 
			'update_user_id' => auth()->id(),
			'update_shop_id' => $validated['shop_id'],
		] );
		$order->delete();
		$totalPrice = Order::where( 'invoice_id', $order->invoice_id )->sum( 'unit_price' );
		$newOrder->invoice->update( [ 
			'total_price' => $totalPrice,
		] );
		return $this->success( new InvoiceResource( Invoice::find( $newOrder->invoice_id ) ) );
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( int $id ) {
		$order = Order::withTrashed()->find( $id );
		if ( ! $order ) {
			throw new NotFoundHttpException();
		}
		Gate::authorize( 'delete', $order );
		if ( $order->returned ) {
			return $this->failure( 'You are trying to delete an order that was previously returned' );
		}
		if ( $order->deleted_at ) {
			return $this->failure( 'You are trying to delete an order that was previously updated' );
		}
		$validated = request()->validate( [ 
			'shop_id' => 'required|exists:shops,id'
		] );
		$order->update( [ 
			'returned' => true,
		] );
		$order->product->update( [ 
			'sold' => false,
			'invoice_id' => null,
		] );
		$order->invoice->update( [ 
			'update_user_id' => auth()->id(),
			'update_shop_id' => $validated['shop_id'],
		] );
		$order->delete();
		$totalPrice = Order::where( 'invoice_id', $order->invoice_id )->sum( 'unit_price' );
		$order->invoice->update( [ 
			'total_price' => $totalPrice,
		] );
		return $this->success(
			new InvoiceResource( Invoice::find( $order->invoice_id ) )
			, message: 'Order Deleted Successfully'
		);
	}
}
