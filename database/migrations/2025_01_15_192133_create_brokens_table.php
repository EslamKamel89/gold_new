<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create( 'brokens', function (Blueprint $table) {
			$table->id();
			$table->foreignId( 'category_id' )->nullable()->constrained()->nullOnDelete();//!
			$table->string( 'seller' )->nullable();
			$table->float( 'weight' )->nullable();
			$table->float( 'price' )->nullable();
			$table->string( 'standard' )->nullable();
			$table->string( 'type' )->nullable();
			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists( 'brokens' );
	}
};
