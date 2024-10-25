<?php
namespace App\Traits;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Validator;
use Exception;
trait InvoiceTrait {
	public function validateInoviceData() {
		$invoiceValidator = Validator::make(
			request()->only( [ 'user_id', 'shop_id', 'code', 'total_price', 'customer_name', 'customer_phone' ] ),
			[ 
				'user_id' => [ 'required', 'exists:users,id' ],
				'shop_id' => [ 'required', 'exists:shops,id' ],
				'code' => [ 'sometimes', 'unique:invoices,code', 'max:255' ],
				// 'total_price' => [ 'required', 'numeric' ],
				'customer_name' => [ 'required', 'max:255' ],
				'customer_phone' => [ 'required', 'max:255' ],
			],
		);
		if ( $invoiceValidator->fails() ) {
			throw new ValidationException( $invoiceValidator );
		}
		return $invoiceValidator->validated();
	}

	public function validateOrdersKeyExist() {
		if ( ! request()->has( 'orders' ) ) {
			throw new Exception( 'orders not found' );
		}
		$data = request()->only( 'orders' );
		$orders = $data['orders'];
		if ( ! is_array( $orders ) ) {
			throw new Exception( 'orders must be an array' );
		}
		if ( ! ( count( $orders ) > 0 ) ) {
			throw new Exception( "Must be at least one order in the invoice" );
		}
		return $orders;
	}

	public function validateProductsAreUnique( $orders ) {
		$productIds = [];
		foreach ( $orders as $k => $order ) {
			$productIds[] = $order["product_id"];
		}
		if ( count( $productIds ) !== count( array_unique( $productIds ) ) ) {
			throw new Exception( "Two or more orders have the same product" );
		}
	}

	public function validateOrders( $orders ) {
		$validatedOrders = [];
		foreach ( $orders as $k => $order ) {
			if ( ! isset( $order['codes'] ) ) {
				throw new Exception( "No items recieved in order: " . $k );
			}
			if ( ! is_array( $order["codes"] ) ) {
				throw new Exception( "Items codes must be sended in an array, order: " . $k );
			}
			if ( ! ( count( $order['codes'] ) > 0 ) ) {
				throw new Exception( 'There must be at least one item in the order: ' . $k );
			}
			$validatedOrder = Validator::make(
				$order,
				[ 
					'product_id' => [ 'required', 'exists:products,id' ],
					// 'quantity' => [ 'required', 'numeric' ],
					'description' => [ 'sometimes', 'max:255' ],
					'unit_price' => [ 'required', 'numeric' ],
					'codes' => [ 'required' ],
				]
			);
			if ( $validatedOrder->fails() ) {
				throw new ValidationException( $validatedOrder );
			}
			$validatedOrders[] = $validatedOrder->validated();
		}
		return $validatedOrders;
	}

	public function validateItemsCodes( $validatedOrders ) {
		foreach ( $validatedOrders as $k => $order ) {
			$codes = $order['codes'];
			// info( 'order', $order );
			// info( 'codes', $codes );
			if ( count( $codes ) !== count( array_unique( $codes ) ) ) {
				throw new Exception( " Item condes are not unique in order: " . $k );
			}
			foreach ( $codes as $i => $code ) {
				$item = Item::where( 'code', $code )->first();
				// info( 'item', [ $item ] );
				if ( ! $item ) {
					throw new Exception( "Can't find any item with the code: " . $code );
				}
				// info( "order['product_id']", [ $order['product_id'] ] );
				// info( "item->order_id", [ $item->order_id ] );
				if ( $order['product_id'] != $item->product_id ) {
					throw new Exception( "Item Code " . $code . " don't belong to product id: " . $order['product_id'] );
				}
				if ( $item->sold ) {
					throw new Exception( "Item with code: " . $code . " is already sold" );
				}
			}
		}
	}
}
