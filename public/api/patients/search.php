<?php

declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Patient;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();

    $keyword = Request::getAsString( "keyword" );

    if ( empty( $keyword ) ) {
        JSONResponse::validResponse( [] );
        return;
    }

    $patients = Patient::search( $keyword );

    JSONResponse::validResponse( $patients );
    return;


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
