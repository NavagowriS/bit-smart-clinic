<?php
declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Patient;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger("id", true);

    $patient = Patient::find($id);

    if (empty($patient)) throw new Exception("No doctor found");

    JSONResponse::validResponse( $patient);

} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
