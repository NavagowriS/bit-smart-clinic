<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();

    $fields = [
        'start_date' => Request::getAsString( 'start_date' ),
        'end_date' => Request::getAsString( 'end_date' ),
        'clinic_id' => Request::getAsInteger( 'clinic_id' ) ?? 0,
    ];

    if ( !empty( $fields[ 'start_date' ] ) && !empty( $fields[ 'end_date' ] ) ) {
        /* dispensed drugs count */
        $output = Drug::getDrugDispenseStats( $fields[ 'start_date' ], $fields[ 'end_date' ], $fields[ 'clinic_id' ] );
        JSONResponse::validResponse( $output );
    }


} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
