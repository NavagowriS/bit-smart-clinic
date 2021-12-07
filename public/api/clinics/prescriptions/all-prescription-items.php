<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "prescription_id", true );

    $prescription = Prescription::find( $id );
    if ( empty( $prescription ) ) throw new Exception( 'Invalid prescription' );

    $items = PrescriptionItem::findByPrescription( $prescription );
    JSONResponse::validResponse( $items );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
