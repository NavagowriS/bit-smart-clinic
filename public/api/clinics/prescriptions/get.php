<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Prescription;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "id", true );

    $prescription = Prescription::find( $id );

    JSONResponse::validResponse( $prescription );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
