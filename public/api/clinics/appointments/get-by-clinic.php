<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;
use App\Models\ClinicAppointment;

require_once '../../../../bootstrap.php';

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        'clinic_id' => Request::getAsInteger( 'clinic_id', true ),
        'start_date' => Request::getAsString( 'start_date' ),
        'end_date' => Request::getAsString( 'end_date' ),
        'status' => Request::getAsString( 'status' ) ?? 'ALL',
    ];

    $clinic = Clinic::find( $fields[ 'clinic_id' ] );

    if ( !empty( $fields[ 'start_date' ] ) && !empty( $fields[ 'end_date' ] ) ) {
        $appointments = ClinicAppointment::getByClinicBetweenDates( $clinic, $fields[ 'start_date' ], $fields[ 'end_date' ], $fields[ 'status' ] );
    } else {
        $appointments = ClinicAppointment::getByClinic( $clinic );
    }


    if ( !empty( $appointments ) ) {
        JSONResponse::validResponse( [ 'appointments' => $appointments ] );
        return;
    }

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
