<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisit;

require_once '../../../../bootstrap.php';

try {

    Auth::authenticate();

    $id = Request::getAsInteger( 'id', true );

    $clinicVisit = ClinicVisit::find( $id );
    if ( empty( $clinicVisit ) ) throw new Exception( 'Invalid clinic visit' );

    JSONResponse::validResponse( $clinicVisit );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
