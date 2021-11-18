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

    $fields = [
        'id' => Request::getAsInteger( 'id', true ),
    ];


    $appointment = ClinicAppointment::build( $fields );

    if ( $appointment->delete() ) {
        JSONResponse::validResponse( 'Deleted' );
        return;
    }

    throw new Exception( 'Failed' );


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
