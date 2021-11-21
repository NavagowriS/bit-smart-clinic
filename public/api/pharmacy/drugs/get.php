<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "id", true );

    $drug = Drug::find( $id );

    if ( empty( $drug ) ) throw new Exception( "No drug found" );

    JSONResponse::validResponse( $drug );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
