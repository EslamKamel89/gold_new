<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray( Request $request ): array {
		return [ 
			'id' => $this->id,
			'userId' => $this->user_id,
			'shopId' => $this->shop_id,
			'totalPrice' => $this->total_price,
			'manufactureCostGram18' => $this->manufacture_cost_gram_18,
			'manufactureCostGram21' => $this->manufacture_cost_gram_21,
			'manufactureCostGram24' => $this->manufacture_cost_gram_24,
			'customerName' => $this->customer_name,
			'customerPhone' => $this->customer_phone,
			'quantity' => $this->quantity,
			'updateUserId' => $this->update_user_id,
			'updateShopId' => $this->update_shop_id,
			'createdAt' => $this->created_at,
			'updatedAt' => $this->updated_at,
			'products' => ProductResource::collection( $this->products ),
			'orders' => OrderResource::collection(
				Order::where( 'invoice_id', $this->id )->withTrashed()->get()
			),
			'invoiceCreator' => new UserResource( $this->invoiceCreator ),
			'invoiceUpdater' => new UserResource( $this->invoiceUpdater ),
			'createdInShop' => new ShopResource( $this->createdInShop ),
			'updatedInShop' => new ShopResource( $this->updatedInShop ),

		];
	}
}
