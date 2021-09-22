<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        "id" => Request::getAsInteger( "id", true ),
        "title" => Request::getAsString( "title", true ),
        "doctor_in_charge_id" => Request::getAsInteger( "doctor_in_charge_id", true ),
    ];


    $patient = Clinic::find( $fields["id"] );
    if ( empty( $patient ) ) throw new Exception( "Invalid clinic" );

    $object = Clinic::build( $fields );

    $result = $object->update();

    if ( $result ) {
        JSONResponse::validResponse( "Updated" );
        return;
    }


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
