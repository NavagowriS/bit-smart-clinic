<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\DrugPurchaseOrder;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        "drug_id" => Request::getAsString( "drug_id", true ),
        "order_date" => Request::getAsString( "order_date", true ),
        "quantity" => Request::getAsInteger( "quantity", true ),
    ];

    $purchaseOrder = DrugPurchaseOrder::build( $fields );

    $result = $purchaseOrder->insert();

    if ( $result ) {

        $purchaseOrder = DrugPurchaseOrder::find( $result );

        if ( !empty( $purchaseOrder ) ) {
            JSONResponse::validResponse( $purchaseOrder );
            return;
        }
    }

    throw new Exception( 'Failed to create new purchase order' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
