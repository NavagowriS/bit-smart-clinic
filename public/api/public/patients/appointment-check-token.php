<?php

declare( strict_types=1 );

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;

require_once "../../../../bootstrap.php";

try {


    $fields = [
        'clinic_id' => Request::getAsInteger( 'clinic_id', true ),
        'clinic_date' => Request::getAsString( 'clinic_date', true ),
        'clinic_patient_id' => Request::getAsInteger( 'clinic_patient_id', true ),
    ];


    if ( ClinicAppointment::checkExist( $fields[ 'clinic_id' ], $fields[ 'clinic_patient_id' ], $fields[ 'clinic_date' ] ) ) {
        throw new Exception( 'Appointment already exist' );
    }


    $appointments = ClinicAppointment::getAppointmentsForDay( $fields[ 'clinic_id' ], $fields[ 'clinic_date' ] );

    if ( !empty( $appointments ) ) {
        $n = count( $appointments );

        $latestAppointment = $appointments[ $n - 1 ];

        JSONResponse::validResponse( [ 'token_number' => $latestAppointment->token_number + 1 ] );
    } else {
        JSONResponse::validResponse( [ 'token_number' => 1 ] );
    }

    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
