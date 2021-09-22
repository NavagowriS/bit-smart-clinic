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
        'id' => Request::getAsInteger( 'id', true ),
    ];


    $clinicVisitPatient = ClinicVisitPatient::build( $fields );

    $result = $clinicVisitPatient->delete();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

    throw new Exception( 'Failed to remove patient from the clinic visit' );


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
