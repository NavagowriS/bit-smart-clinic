<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Prescription;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $fields = [
        'start_date' => Request::getAsString( 'start_date', true ),
        'end_date' => Request::getAsString( 'end_date', true ),
        'status' => Request::getAsString( 'status' ),
        'clinic_id' => Request::getAsInteger( 'clinic_id' ),
    ];

    $prescriptions = Prescription::findBetweenDates( $fields[ 'start_date' ], $fields[ 'end_date' ], $fields[ 'status' ], $fields[ 'clinic_id' ] );

    JSONResponse::validResponse( $prescriptions );
    return;

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
