<?php

use App\Helpers\ItemCountHelper;
use App\Helpers\LogHelper;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GoldPriceController;
use App\Http\Controllers\CreditorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TraderController;
use App\Models\Item;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get( '/user', function (Request $request) {
	return $request->user();
} )->middleware( 'auth:sanctum' );


Route::prefix( '/auth' )->group( function () {
	Route::post( '/login', [ AuthController::class, 'login' ] )
		->name( 'apiLogin' );
	Route::post( '/register', [ AuthController::class, 'register' ] )
		->name( 'apiRegister' );
	Route::get( '/forget-password', [ AuthController::class, 'forgetPassword' ] )
		->name( 'apiForgetPassword' )->middleware( [ 'auth:sanctum' ] );
} );

Route::middleware( [ 'auth:sanctum' ] )->group( function () {
	Route::apiResource( '/shops', ShopController::class);
	Route::apiResource( '/categories', CategoryController::class);
	Route::apiResource( '/producers', ProducerController::class);
	Route::apiResource( '/products', ProductController::class);
	// Route::apiResource( '/items', ItemController::class);
	Route::apiResource( '/gold-prices', GoldPriceController::class);
	Route::apiResource( '/traders', TraderController::class);
	Route::apiResource( '/invoices', InvoiceController::class)
		->only( [ 'index', 'store', 'show' ] );
	Route::apiResource( '/orders', OrderController::class);
} );

// Route::get( 'test', function () {
// 	$item = Item::find( 2 );
// 	LogHelper::_( $item->product );
// 	ItemCountHelper::decrementItemCount( $item );
// 	LogHelper::_( $item->product );

// } );

