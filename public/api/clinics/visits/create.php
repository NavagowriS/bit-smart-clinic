<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicVisit;

require_once '../../../../bootstrap.php';

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        'clinic_id' => Request::getAsInteger( 'clinic_id', true ),
        'visit_date' => Request::getAsString( 'visit_date', true ),
    ];


    $object = ClinicVisit::build( $fields );

    $result = $object->insert();

    if ( $result ) {

        $object = ClinicVisit::find( $result );

        JSONResponse::validResponse( [ 'clinic_visit' => $object ] );
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
