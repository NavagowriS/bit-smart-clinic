<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicPatient;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "id", true );

    $clinicPatient = ClinicPatient::find( $id );

    JSONResponse::validResponse( $clinicPatient );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
