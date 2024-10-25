<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray( Request $request ): array {
		return [ 
			'id' => $this->id,
			'productId' => $this->product_id,
			'invoiceId' => $this->invoice_id,
			'unitPrice' => $this->unit_price,
			'product' => new ProductResource( $this->whenLoaded( 'product' ) ),
			'invoice' => new InvoiceResource( $this->whenLoaded( 'invoice' ) ),
			'createdAt' => $this->created_at,
			'updatedAt' => $this->updated_at,
		];
	}
}
