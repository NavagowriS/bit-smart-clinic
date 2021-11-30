<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\PharmacyTag;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        "id" => Request::getAsInteger( "id", true ),
    ];

    $tag = PharmacyTag::build( $fields );

    $result = $tag->delete();

    if ( $result ) {
        JSONResponse::validResponse( 'Tag deleted' );
        return;
    }

    throw new Exception( 'Failed to delete the tag' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
