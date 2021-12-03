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
        "id" => Request::getAsString( "id", true ),
    ];

    $purchaseOrder = DrugPurchaseOrder::build( $fields );

    $result = $purchaseOrder->delete();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

    throw new Exception( 'Failed to delete the purchase order' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
