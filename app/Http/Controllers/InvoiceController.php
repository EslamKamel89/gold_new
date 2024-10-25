<?php
namespace App\Http\Controllers;
use App\Helpers\InvocieHelper;
use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\InvoiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller {
	use ApiResponse, InvoiceTrait;

	public function __construct(
		private InvocieHelper $invoiceHelper,
	) {
	}
	/**
	 * Display a listing of the resource.
	 */
	public function index( Request $requesdt ) {
		return $this->success(
			InvoiceResource::collection(
				Invoice::paginate( request()->get( 'limit' ) ?? 10 )
			)
		);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store( Request $request ) {
		Gate::authorize( 'create', Invoice::class);
		try {
			//? validate invoice data
			$inoviceValidated = $this->validateInoviceData();
			//? validate that the orders are recieved and there are at least one order
			$orders = $this->validateOrdersKeyExist();
			//? validate if each product are unique and there are no duplicats
			$this->validateProductsAreUnique( $orders );
			//? validate the orders
			$validatedOrders = $this->validateOrders( $orders );
			//? calcuate invoice total price and merge it with the invoiceValidated array
			$inoviceValidated['total_price'] = $this->invoiceHelper->getTotalInvoicePrice( $validatedOrders );
			$inoviceValidated['user_id'] = auth()->id();
			$inoviceValidated['quantity'] = count( $validatedOrders );
			$invoice = Invoice::create( $inoviceValidated );
			foreach ( $validatedOrders as $order ) {
				$invoice->orderProducts()->attach(
					$order['product_id'],
					[ 
						"unit_price" => $order["unit_price"],
						"description" => $order["description"],
					],
				);
				$product = Product::find( $order["product_id"] );
				$product->update( [ 
					'sold' => true,
				] );
			}
			return $this->success( new InvoiceResource( $invoice ), message: "Order Placed Successfully" );
		} catch (ValidationException $validationException) {
			throw $validationException;
		} catch (\Throwable $th) {
			return $this->failure( $th->getMessage() );
		}
	}



	/**
	 * Display the specified resource.
	 */
	public function show( Invoice $invoice ) {
		return $this->success( new InvoiceResource( $invoice ) );
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update( Request $request, Invoice $invoice ) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy( Invoice $invoice ) {
		//
	}
}
