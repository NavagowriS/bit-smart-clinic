<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;
use App\Models\Pharmacy\DrugsTag;

require_once "../../../../bootstrap.php";

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        "drug_id" => Request::getAsInteger( "drug_id", true ),
    ];

    $drug = Drug::find( $fields[ 'drug_id' ] );

    if ( empty( $drug ) ) throw new Exception( 'Invalid drug' );

    $drugTags = DrugsTag::findByDrug( $drug );

    if ( !empty( $drugTags ) ) {
        JSONResponse::validResponse( $drugTags );
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
