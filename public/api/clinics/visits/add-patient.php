<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisitPatient;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $fields = [
        'clinic_patient_id' => Request::getAsInteger( 'clinic_patient_id', true ),
        'clinic_visit_id' => Request::getAsInteger( 'clinic_visit_id', true ),
    ];


    $clinicVisitPatient = ClinicVisitPatient::build( $fields );

    if ( $clinicVisitPatient->alreadyExist() ) {
        throw new Exception( 'Patient is already added to the visit' );
    }

    $result = $clinicVisitPatient->insert();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

    throw new Exception( 'Failed to add patient to the clinic visit' );


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
