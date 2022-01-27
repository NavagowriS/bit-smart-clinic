<?php

declare(strict_types=1);

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;

require_once "../../../../bootstrap.php";

try {


    $fields = [
        'id' => Request::getAsInteger('id', true),
    ];

    $appointment = ClinicAppointment::build($fields);
    $result = $appointment->delete();

    if (!empty($result)) {

        JSONResponse::validResponse();
        return;
    }

    JSONResponse::invalidResponse('Failed to cancel an appointment');
    return;


} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
