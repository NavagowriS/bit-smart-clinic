<?php

declare( strict_types=1 );

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;
use App\Models\ClinicPatient;

require_once "../../../../bootstrap.php";

try {


    $clinicPatientId = Request::getAsInteger( 'clinic_patient_id', true );

    $clinicPatient = ClinicPatient::find( $clinicPatientId );

    if ( !empty( $clinicPatient ) ) {

        $clinics = Clinic::find( $clinicPatient->clinic_id );

        JSONResponse::validResponse( $clinics );
        return;
    }

    JSONResponse::invalidResponse( 'Invalid clinic patient id' );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
