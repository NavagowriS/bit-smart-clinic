<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $drugs = Drug::findAll();

    if ( empty( $drugs ) ) throw new Exception( "No drugs found" );

    JSONResponse::validResponse( $drugs );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
