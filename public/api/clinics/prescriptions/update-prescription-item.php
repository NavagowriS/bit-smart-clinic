<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\PrescriptionItem;

require_once "../../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        "id" => Request::getAsInteger( "id", true ),
        "dose" => Request::getAsInteger( "dose", true ),
        "frequency" => Request::getAsString( "frequency" ),
        "period" => Request::getAsInteger( "period", true ),
        "remarks" => Request::getAsString( "remarks" ),
    ];


    $object = PrescriptionItem::build( $fields );

    $result = $object->update();

    if ( $result ) {
        JSONResponse::validResponse( "Updated" );
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
