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
        "drug_name" => Request::getAsString( "drug_name", true ),
        "generic_name" => Request::getAsString( "generic_name" ),
        "brand_name" => Request::getAsString( "brand_name" ),
    ];

    $drug = Drug::build( $fields );

    $result = $drug->insert();

    if ( $result ) {

        $drug = Drug::find( $result );

        if ( !empty( $drug ) ) {
            JSONResponse::validResponse( $drug );
            return;
        }
    }

    throw new Exception( 'Failed to create new drug' );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
