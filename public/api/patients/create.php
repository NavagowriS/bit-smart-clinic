<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Patient;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        "full_name" => Request::getAsString( "full_name", true ),
        "dob" => Request::getAsString( "dob" ),
        "age" => Request::getAsInteger( "age" ),
        "phone" => Request::getAsString( "phone" ),
        "nic" => Request::getAsString( "nic" ),
        "address" => Request::getAsString( "address" ),
        "guardian_name" => Request::getAsString( "guardian_name" ),
        "guardian_phone" => Request::getAsString( "guardian_phone" ),
        "gender" => Request::getAsString( "gender", true ),
    ];


    $object = Patient::build( $fields );

    $result = $object->insert();

    if ( $result ) {

        $object = Patient::find( $result );

        JSONResponse::validResponse( [ "patient" => $object ] );
        return;
    }


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
