<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;
use App\Models\Prescription;

require_once "../../../../bootstrap.php";

try {

//    Auth::authenticate();

    $id = Request::getAsInteger( "appointment_id", true );

    $appointment = ClinicAppointment::find( $id );

    if ( empty( $appointment ) ) throw new Exception( 'Invalid appointment' );

    $prescription = Prescription::findByAppointment( $appointment );
    JSONResponse::validResponse( $prescription );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
