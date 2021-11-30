<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\DrugsTag;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        "id" => Request::getAsInteger( "tag_id", true ),
    ];

    $drugTag = DrugsTag::build( $fields );

    $result = $drugTag->delete();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

    throw new Exception( 'Failed to add new tag' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
