<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Patient;
use Carbon\Carbon;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        "id" => Request::getAsInteger( "id", true ),
        "full_name" => Request::getAsString( "full_name", true ),
        "dob" => Request::getAsString( "dob", true ),
        "age" => Request::getAsInteger( "age" ),
        "phone" => Request::getAsString( "phone" ),
        "nic" => Request::getAsString( "nic" ),
        "address" => Request::getAsString( "address" ),
        "guardian_name" => Request::getAsString( "guardian_name" ),
        "guardian_address" => Request::getAsString( "guardian_address" ),
        "guardian_phone" => Request::getAsString( "guardian_phone" ),
        "gender" => Request::getAsString( "gender" ),
        "login_pass" => Request::getAsString( "login_pass" ),
//        "login_name" => Request::getAsString( "login_pass", true ),
    ];


    $patient = Patient::find( $fields["id"] );
    if ( empty( $patient ) ) throw new Exception( "Invalid patient" );

    $object = Patient::build( $fields );

    try {
        new Carbon( $fields["dob"] );
    } catch ( Exception $exception ) {
        throw new Exception( "Invalid dob" );
    }

    $result = $object->update();

    if ( $result ) {
        JSONResponse::validResponse( "Updated" );
        return;
    }


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
