<?php
declare( strict_types=1 );

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Models\Pharmacy\Drug;

require_once "../../../../bootstrap.php";

try {

    Auth::authenticate();
    $output = [
        'threshold_warnings' => [],
    ];

    $drugs = Drug::findAll();

    if ( !empty( $drugs ) ) {


        foreach ( $drugs as $drug ) {
            if ( $drug->total_count <= $drug->min_quantity ) {
                $output['threshold_warnings'][] = $drug;
            }

        }

    }

    JSONResponse::validResponse( $output );

} catch ( Exception $exception ) {
    JSONResponse::exceptionResponse( $exception );
}
