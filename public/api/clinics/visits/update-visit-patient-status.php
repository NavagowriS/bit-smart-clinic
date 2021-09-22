<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisitPatient;

require_once '../../../../bootstrap.php';

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();

    $STATUS_VALUES = [ 'ACTIVE', 'COMPLETED', 'CANCELLED', 'MISSED' ];


    $fields = [
        'id' => Request::getAsInteger( 'id', true ), // clinic visit patient id
        'status' => strtoupper( Request::getAsString( 'status' ) ), // clinic visit patient status
    ];

    /* check if status is valid */
    if ( !in_array( $fields[ 'status' ], $STATUS_VALUES ) ) {
        throw new Exception( 'Invalid status given' );
    }

    $object = ClinicVisitPatient::build( $fields );

    $result = $object->updateStatus();

    if ( $result ) {

        JSONResponse::validResponse();
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
