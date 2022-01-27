<?php

declare(strict_types=1);

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\ClinicAppointment;

require_once "../../../../bootstrap.php";

try {


    $fields = [
        'clinic_id' => Request::getAsInteger('clinic_id', true),
        'clinic_date' => Request::getAsString('clinic_date', true),
        'clinic_patient_id' => Request::getAsInteger('clinic_patient_id', true),
    ];

    $existing = ClinicAppointment::checkExist($fields['clinic_id'], $fields['clinic_patient_id'], $fields['clinic_date']);

    $usedTokens = ClinicAppointment::getAllTokensForTheDay($fields['clinic_id'], $fields['clinic_date']);

    JSONResponse::validResponse([
        'usedTokens' => $usedTokens,
        'existing' => $existing
    ]);
    return;


} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
