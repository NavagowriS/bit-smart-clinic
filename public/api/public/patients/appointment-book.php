<?php

declare( strict_types=1 );

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;

require_once "../../../../bootstrap.php";

try {


    $fields = [
        'clinic_id' => Request::getAsInteger( 'clinic_id', true ),
        'clinic_patient_id' => Request::getAsInteger( 'clinic_patient_id', true ),
        'token_number' => Request::getAsInteger( 'token_number', true ),
        'clinic_date' => Request::getAsString( 'clinic_date', true ),
    ];


    $appointment = ClinicAppointment::build( $fields );

    $result = $appointment->insert();


    if ( !empty( $result ) ) {

        JSONResponse::validResponse();
        return;
    }

    JSONResponse::invalidResponse( 'Failed to book an appointment' );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
