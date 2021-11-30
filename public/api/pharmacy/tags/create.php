<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;
use App\Models\Pharmacy\PharmacyTag;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        "tag" => Request::getAsString( "tag", true ),
    ];

    $tag = PharmacyTag::build( $fields );

    $result = $tag->insert();

    if ( $result ) {

        $tag = PharmacyTag::find( $result );

        if ( !empty( $tag ) ) {
            JSONResponse::validResponse( $tag );
            return;
        }
    }

    throw new Exception( 'Failed to create new tag' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
