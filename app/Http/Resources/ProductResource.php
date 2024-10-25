<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray( Request $request ): array {
		return [ 
			'id' => $this->id,
			'name' => $this->name,
			'price' => $this->price,
			'sold' => $this->sold,
			'code' => $this->code,
			'weight' => $this->weight,
			'manufactureCost' => $this->manufacture_cost,
			'category' => new CategoryResource( $this->whenLoaded( 'category' ) ),
			'producer' => new ProducerResource( $this->whenLoaded( 'producer' ) ),
			'trader' => new ProducerResource( $this->whenLoaded( 'trader' ) ),
			'shop' => new ProducerResource( $this->whenLoaded( 'shop' ) ),
			'invoice' => new ProducerResource( $this->whenLoaded( 'invoice' ) ),
			'goldPrice' => new ProducerResource( $this->whenLoaded( 'goldPrice' ) ),
			'createdAt' => $this->created_at,
			'updatedAt' => $this->updated_at,
		];
	}
}
