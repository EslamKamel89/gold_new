<?php
namespace App\Helpers;
class LogHelper {
	static public function _( $data, ?string $title = null ): mixed {
		$message = json_encode( $data );
		if ( $title != null ) {
			$message = "$title - $message";
		}
		info( $message );
		return $data;
	}
}
