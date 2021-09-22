<?php
declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Clinic;

require_once "../../../bootstrap.php";

try {

    Auth::authenticate();

    $id = Request::getAsInteger("id", true);

    $patient = Clinic::find($id);

    if (empty($patient)) throw new Exception("No clinic found");

    JSONResponse::validResponse( $patient);

} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
