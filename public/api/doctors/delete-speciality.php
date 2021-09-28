<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\DoctorSpeciality;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        'id' => Request::getAsString( 'id', true ),
    ];

    $speciality = DoctorSpeciality::build( $fields );

    $result = $speciality->delete();

    if ( $result != 1 ) {
        JSONResponse::validResponse( 'Speciality created' );
        return;
    }
    JSONResponse::invalidResponse( 'Failed to create specialty' );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
