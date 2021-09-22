<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisit;
use App\Models\ClinicVisitPatient;

require_once '../../../../bootstrap.php';

try {

    Auth::authenticate();

    $id = Request::getAsInteger( 'clinic_visit_id', true );

    $clinicVisit = ClinicVisit::find( $id );

    $clinicVisitPatients = ClinicVisitPatient::findByClinicVisit( $clinicVisit );

    JSONResponse::validResponse( $clinicVisitPatients );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
