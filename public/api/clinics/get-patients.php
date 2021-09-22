<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;
use App\Models\ClinicPatient;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "id", true );

    $clinic = Clinic::find( $id );

    if ( empty( $clinic ) ) throw new Exception( "Invalid clinic" );

    $patients = ClinicPatient::findByClinic( $clinic );

//    if ( empty( $patients ) ) throw new Exception( "No patients found" );

    JSONResponse::validResponse( $patients );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
