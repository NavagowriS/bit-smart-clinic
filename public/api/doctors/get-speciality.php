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
        'id' => Request::getAsInteger( 'id', true ),
    ];

    $speciality = DoctorSpeciality::find( $fields[ 'id' ] );

    JSONResponse::validResponse( $speciality );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
