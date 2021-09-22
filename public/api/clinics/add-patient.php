<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicPatient;

require_once '../../../bootstrap.php';


try {

    Auth::authenticate();

    $fields = [
        "clinic_id" => Request::getAsInteger( "clinic_id", true ),
        "patient_id" => Request::getAsInteger( "patient_id", true ),
        "since" => Request::getAsString( "since", true ),
    ];


    /* check if patient is already added to the clinic */

    $result = ClinicPatient::findByPatient( $fields[ "clinic_id" ], $fields[ "patient_id" ] );

    if ( !empty( $result ) ) throw new Exception( "Patient is already in the clinic" );


    $clinicPatient = ClinicPatient::build( $fields );

    $result = $clinicPatient->insert();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

    throw new Exception( "Failed to save patient to the clinic" );


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
