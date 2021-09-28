<?php

declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Doctor;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    /* id: user_id which is used to login */
    $id = Request::getAsInteger("id", true);

    $doctor = Doctor::findByAssociatedUser($id);

    if (empty($doctor)) throw new Exception("No doctor profile found");

    JSONResponse::validResponse($doctor);
} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
