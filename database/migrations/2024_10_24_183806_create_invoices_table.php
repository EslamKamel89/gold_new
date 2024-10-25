<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create( 'invoices', function (Blueprint $table) {
			$table->id();
			$table->foreignId( 'user_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'shop_id' )->nullable()->constrained()->nullOnDelete();
			$table->float( 'total_price' );
			$table->float( 'manufacture_cost_gram' );
			$table->string( 'customer_name' );
			$table->string( 'customer_phone' );
			$table->integer( 'quantity' );
			$table->foreignId( 'update_user_id' )->nullable()->constrained( 'users' )->nullOnDelete();
			$table->foreignId( 'update_shop_id' )->nullable()->constrained( 'shops' )->nullOnDelete();
			$table->softDeletes();
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists( 'invoices' );
	}
};
