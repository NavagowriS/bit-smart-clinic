<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;
use App\Models\Pharmacy\DrugPurchaseOrder;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();


    $drugId = Request::getAsInteger( 'drug_id', true );

    $drug = Drug::find( $drugId );

    if ( empty( $drug ) ) throw new Exception( 'Invalid drug' );

    $purchaseOrders = DrugPurchaseOrder::findByDrug( $drug );
    JSONResponse::validResponse( $purchaseOrders );
    return;

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
