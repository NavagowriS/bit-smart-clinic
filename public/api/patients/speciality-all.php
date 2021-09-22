<?php

declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Models\DoctorSpeciality;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();

    $doctors = DoctorSpeciality::findAll();

    JSONResponse::validResponse($doctors);
    return;


} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
