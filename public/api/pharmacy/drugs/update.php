<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        'id' => Request::getAsInteger( 'id', true ),
        "drug_name" => Request::getAsString( "drug_name", true ),
        "generic_name" => Request::getAsString( "generic_name" ),
        "brand_name" => Request::getAsString( "brand_name" ),
    ];

    $drug = Drug::find( $fields[ 'id' ] );

    if ( empty( $drug ) ) throw new Exception( 'Invalid drug' );

    $drug = Drug::build( $fields );
    $result = $drug->update();

    if ( $result ) {
        JSONResponse::validResponse();
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
