<?php

declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSpeciality;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        'name' => Request::getAsString('name', true),
        'email' => Request::getAsString('email', true),
        'dob' => Request::getAsString('dob'),
        'phone' => Request::getAsString('phone'),
        'speciality_id' => Request::getAsInteger('speciality_id', true),
    ];


    if ($fields["speciality_id"] < 0) throw new Exception("Invalid Speciality");

    /* check if speciality is valid */
    $speciality = DoctorSpeciality::find($fields["speciality_id"]);

    if (empty($speciality)) throw new Exception("Invalid speciality");

    $doctor = Doctor::build($fields);

    $result = $doctor->insert();

    if ($result) {

        $doctor = Doctor::find($result);

        JSONResponse::validResponse(['doctor' => $doctor]);
        return;
    }


} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
