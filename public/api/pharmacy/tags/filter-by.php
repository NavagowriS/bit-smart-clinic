<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\PharmacyTag;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger( "id", true );

    $tag = PharmacyTag::find( $id );

    if ( empty( $tag ) ) throw new Exception( "No tag found" );


    $drugs = $tag->findAllDrugs();

    JSONResponse::validResponse( $drugs );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
