<?php

namespace App\Http\Controllers;

use App\Models\GoldPrice;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller {
	use ApiResponse;
	public function dailyReport( Request $request ) {
		$date = request( 'date' ) ?? '2025-1-5';
		$shopId = request( 'shopId' ) ?? 3;
		return $this->success( collect(
			[ 
				'total_added' => $this->totalAddedGrams( $date, $shopId ),
				'total_sold' => $this->totalSoldProducts( $date, $shopId ),
				'total_money' => $this->totalMoney( $date, $shopId ),
			]
		) );

	}
	protected function totalAddedGrams(
		string $date,
		int $shopId,
		// int $goldPriceId
	) {
		return Product::selectRaw( 'gold_price_id  , SUM(weight) as total_added_grams' )
			->whereBetween( 'created_at', [ 
				Carbon::parse( $date )->startOfDay(),
				Carbon::parse( $date )->endOfDay()
			] )
			->where( 'shop_id', $shopId )
			->groupBy( 'gold_price_id' )
			->with( 'goldPrice' )
			->get();
	}
	protected function totalSoldProducts(
		string $date,
		int $shopId,
	) {

		$results = Order::join( 'products', 'orders.product_id', '=', 'products.id' )
			->join( 'invoices', 'orders.invoice_id', '=', 'invoices.id' )
			// ->with( 'product.goldPrice' )
			->selectRaw( 'products.gold_price_id  , SUM(weight) as total_sold_grams' )
			->where( 'invoices.shop_id', $shopId )
			->where( 'returned', 0 )
			->whereBetween( 'orders.created_at', [ 
				Carbon::parse( $date )->startOfDay(),
				Carbon::parse( $date )->endOfDay()
			] )
			->groupBy( 'products.gold_price_id' )
			->get();
		return collect( $results )
			->each( fn( $result ) => $result['gold_price'] = GoldPrice::find( $result['gold_price_id'] ) );
		;
	}
	protected function totalMoney(
		string $date,
		int $shopId,
	) {
		return Invoice::selectRaw( 'SUM(total_price) as total_money' )
			->where( 'shop_id', $shopId )
			->whereBetween( 'created_at', [ 
				Carbon::parse( $date )->startOfDay(),
				Carbon::parse( $date )->endOfDay()
			] )->get();
	}
}
