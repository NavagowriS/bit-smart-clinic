<?php

declare(strict_types=1);

use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Patient;

require_once "../../../bootstrap.php";

try {

    $fields = [
        'username' => Request::getAsString('username'),
        'password' => Request::getAsString('password'),
    ];

    $patient = Patient::loginPatient($fields['username'], $fields['password']);

    if (!empty($patient)) {
        JSONResponse::validResponse($patient);
        return;
    }

    JSONResponse::invalidResponse('Login failed');
    return;
} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
