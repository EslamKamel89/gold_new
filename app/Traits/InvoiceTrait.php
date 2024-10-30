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
			request()->only(
				[ "shop_id", "manufacture_cost_gram", "customer_name", "customer_phone", 'manufacture_cost_gram_18', 'manufacture_cost_gram_21', 'manufacture_cost_gram_24',]
			),
			[ 
				"shop_id" => [ "required", "exists:shops,id" ],
				"customer_name" => [ "required", "max:255" ],
				"customer_phone" => [ "required", "max:255" ],
				'manufacture_cost_gram_18' => [ 'sometimes', 'numeric' ],
				'manufacture_cost_gram_21' => [ 'sometimes', 'numeric' ],
				'manufacture_cost_gram_24' => [ 'sometimes', 'numeric' ],
			],
		);
		if ( $invoiceValidator->fails() ) {
			throw new ValidationException( $invoiceValidator );
		}
		return $invoiceValidator->validated();
	}

	public function validateOrdersKeyExist() {
		if ( ! request()->has( "orders" ) ) {
			throw new Exception( "orders not found" );
		}
		$data = request()->only( "orders" );
		$orders = $data["orders"];
		if ( ! is_array( $orders ) ) {
			throw new Exception( "orders must be an array" );
		}
		if ( ! ( count( $orders ) > 0 ) ) {
			throw new Exception( "Must be at least one order in the invoice" );
		}
		return $orders;
	}

	public function validateProductsAreUnique( $orders ) {
		$productIds = [];
		foreach ( $orders as $k => $order ) {
			$productId = $order["product_id"];
			$productIds[] = $productId;
			$product = Product::find( $productId );
			if ( $product->sold ) {
				throw new Exception( "Product with id: $productId is sold" );
			}
		}
		if ( count( $productIds ) !== count( array_unique( $productIds ) ) ) {
			throw new Exception( "Two or more orders have the same product" );
		}
	}

	public function validateOrders( $orders ) {
		$validatedOrders = [];
		foreach ( $orders as $k => $order ) {
			$validatedOrder = Validator::make(
				$order,
				[ 
					"product_id" => [ "required", "exists:products,id" ],
					"description" => [ "sometimes", "max:255" ],
					"unit_price" => [ "required", "numeric" ],
				]
			);
			if ( $validatedOrder->fails() ) {
				throw new ValidationException( $validatedOrder );
			}
			$validatedOrders[] = $validatedOrder->validated();
		}
		return $validatedOrders;
	}

	// public function validateItemsCodes( $validatedOrders ) {
	// 	foreach ( $validatedOrders as $k => $order ) {
	// 		$codes = $order["codes"];
	// 		// info( "order", $order );
	// 		// info( "codes", $codes );
	// 		if ( count( $codes ) !== count( array_unique( $codes ) ) ) {
	// 			throw new Exception( " Item condes are not unique in order: " . $k );
	// 		}
	// 		foreach ( $codes as $i => $code ) {
	// 			$item = Item::where( "code", $code )->first();
	// 			// info( "item", [ $item ] );
	// 			if ( ! $item ) {
	// 				throw new Exception( "Can't find any item with the code: " . $code );
	// 			}
	// 			// info( "order["product_id"]", [ $order["product_id"] ] );
	// 			// info( "item->order_id", [ $item->order_id ] );
	// 			if ( $order["product_id"] != $item->product_id ) {
	// 				throw new Exception( "Item Code " . $code . " don't belong to product id: " . $order["product_id"] );
	// 			}
	// 			if ( $item->sold ) {
	// 				throw new Exception( "Item with code: " . $code . " is already sold" );
	// 			}
	// 		}
	// 	}
	// }
}
