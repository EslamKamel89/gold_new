<?php
declare(strict_types=1);
namespace App\Helpers;
use App\Models\Product;
class InvocieHelper {
	public function addPriceToEachOrder( array $orders ): array {
		foreach ( $orders as $k => $order ) {
			LogHelper::_( $order );
			$orders[ $k ]['quantity'] = count( $order['codes'] );
			$orders[ $k ]['total_price'] = count( $order['codes'] ) * $order['unit_price'];
		}
		LogHelper::_( $orders );

		return $orders;
	}

	public function getTotalInvoicePrice( array $orders ): float {
		$invoicePrice = 0;
		foreach ( $orders as $k => $order ) {
			$invoicePrice += $order['total_price'];
		}
		return $invoicePrice;
	}


}
