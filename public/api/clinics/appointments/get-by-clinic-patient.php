<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;
use App\Models\ClinicPatient;

require_once '../../../../bootstrap.php';

try {

    /*
    * Authenticate for incoming auth key
    * if no valid key is present, will return 401
    * */
    Auth::authenticate();


    $fields = [
        'clinic_patient_id' => Request::getAsInteger( 'clinic_patient_id', true ),
    ];

    $clinicPatient = ClinicPatient::find( $fields[ 'clinic_patient_id' ] );

    if ( empty( $clinicPatient ) ) throw new Exception( 'Invalid clinic patient' );


    $appointments = ClinicAppointment::getByClinicPatient( $clinicPatient->id );

    JSONResponse::validResponse( [ 'appointments' => $appointments ] );
    return;

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
