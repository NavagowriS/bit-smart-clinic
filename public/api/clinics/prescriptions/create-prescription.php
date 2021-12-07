<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Prescription;

require_once "../../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        "appointment_id" => Request::getAsInteger( "appointment_id", true ),
        "prescription_date" => Request::getAsString( "prescription_date", true ),
        "remarks" => Request::getAsString( "remarks" ),
    ];


    $object = Prescription::build( $fields );

    $result = $object->insert();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
