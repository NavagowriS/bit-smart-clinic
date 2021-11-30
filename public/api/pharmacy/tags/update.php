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
        'id' => Request::getAsInteger( 'id', true ),
        "tag" => Request::getAsString( "tag", true ),
    ];

    $tag = PharmacyTag::find( $fields[ 'id' ] );

    if ( empty( $tag ) ) throw new Exception( 'Invalid tag' );

    $tag = PharmacyTag::build( $fields );
    $result = $tag->update();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
