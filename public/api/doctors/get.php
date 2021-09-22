<?php
declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Doctor;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger("id", true);

    $doctor = Doctor::find($id);

    if (empty($doctor)) throw new Exception("No doctor found");

    JSONResponse::validResponse($doctor);

} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
