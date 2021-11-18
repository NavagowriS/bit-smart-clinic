<?php

declare( strict_types=1 );

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;
use App\Models\Patient;

require_once "../../../../bootstrap.php";

try {


    $patientId = Request::getAsInteger( 'id', true );

    $patient = Patient::find( $patientId );

    if ( !empty( $patient ) ) {
        $clinics = Clinic::findByPatient( $patient );

        JSONResponse::validResponse( $clinics );
        return;
    }

    JSONResponse::invalidResponse( 'Invalid patient' );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
