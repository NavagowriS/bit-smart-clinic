<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisitPatient;

require_once '../../../../bootstrap.php';

try {

    Auth::authenticate();

    $id = Request::getAsInteger( 'id', true );

    $clinicVisitPatient = ClinicVisitPatient::find( $id );
    if ( empty( $clinicVisitPatient ) ) throw new Exception( 'Invalid clinic visit patient' );

    JSONResponse::validResponse( $clinicVisitPatient );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
