<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create( 'products', function (Blueprint $table) {
			$table->id();
			$table->foreignId( 'category_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'producer_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'trader_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'shop_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'invoice_id' )->nullable()->constrained()->nullOnDelete();
			$table->foreignId( 'gold_price_id' )->nullable()->constrained( 'gold_prices' )->nullOnDelete();
			$table->string( 'code' )->unique();
			$table->string( 'name' )->nullable();
			$table->float( 'price' )->nullable();
			$table->float( 'weight' );
			$table->float( 'manufacture_cost' )->nullable();
			$table->boolean( 'sold' )->default( false );
			$table->softDeletes();
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists( 'products' );
	}
};
