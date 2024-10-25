<?php
namespace App\Helpers;
use App\Models\Item;
use App\Models\Product;
class ItemCountHelper {
	public static function incrementItemCount( Item $item ) {
		if ( $item->sold ) {
			throw new \Exception( "can't increment product quantity with a sold product" );
		}
		$product = $item->product;
		$quantity = $product->quantity;
		$product->update( [ 
			'quantity' => ++$quantity,
		] );
	}
	public static function decrementItemCount( Item $item, int $invoiceId ) {
		if ( $item->sold ) {
			throw new \Exception( "You are trying to decrement Product count with an item that is already sold" );
		}
		$product = $item->product;
		$quantity = $product->quantity;
		$item->update( [ 
			'sold' => true,
			'invoice_id' => $invoiceId,
		] );
		$product->update( [ 
			'quantity' => --$quantity,
		] );
	}
}
