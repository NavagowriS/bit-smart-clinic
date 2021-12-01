<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $keyword = Request::getAsString( 'keyword' );

    $drugs = Drug::search( $keyword );

    JSONResponse::validResponse( $drugs );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
