<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Models\Pharmacy\Drug;
use App\Models\Pharmacy\PharmacyTag;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $tags = PharmacyTag::findAll();

    if ( empty( $tags ) ) throw new Exception( "No drug tags found" );

    JSONResponse::validResponse( $tags );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
