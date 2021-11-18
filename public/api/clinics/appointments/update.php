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
        'status' => Request::getAsString( 'status' ),
        'param_weight' => Request::getAsInteger( 'param_weight' ),
        'param_height' => Request::getAsFloat( 'param_height' ),
        'param_sbp' => Request::getAsInteger( 'param_sbp' ),
        'param_dbp' => Request::getAsInteger( 'param_dbp' ),
        'param_blood_sugar' => Request::getAsInteger( 'param_blood_sugar' ),
        'doctor_remarks' => Request::getAsString( 'doctor_remarks' ),
    ];


    $appointment = ClinicAppointment::build( $fields );

    if ( $appointment->update() ) {
        JSONResponse::validResponse( 'Updated' );
        return;
    }

    throw new Exception( 'Failed' );


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
