<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;
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
    ];

    $clinic = Clinic::find( $fields[ 'clinic_id' ] );

    $visits = ClinicVisit::getByClinic( $clinic );

    if ( !empty( $visits ) ) {
        JSONResponse::validResponse( [ 'visits' => $visits ] );
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
