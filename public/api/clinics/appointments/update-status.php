<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;

require_once '../../../../bootstrap.php';

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();

    $STATUS_VALUES = [ 'ACTIVE', 'COMPLETED', 'CANCELLED', 'MISSED' ];


    $fields = [
        'id' => Request::getAsInteger( 'id', true ),
        'status' => strtoupper( Request::getAsString( 'status' ) ),
    ];

    /* check if status is valid */
    if ( !in_array( $fields[ 'status' ], $STATUS_VALUES ) ) {
        throw new Exception( 'Invalid status given' );
    }

    $object = ClinicAppointment::build( $fields );

    $result = $object->updateStatus();

    if ( $result ) {

        JSONResponse::validResponse();
        return;
    }


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
