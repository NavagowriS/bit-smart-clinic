<?php

declare(strict_types=1);

use App\Core\Http\Auth;
use App\Core\Http\JSONResponse;
use App\Core\Http\Request;
use App\Models\User;

require_once "../../../bootstrap.php";

try {

    /*
     * Authenticate for incoming auth key
     * if no valid key is present, will return 401
     * */
    Auth::authenticate();


    $fields = [
        'full_name' => Request::getAsString('full_name', true),
        'username' => Request::getAsString('username', true),
        'password' => Request::getAsString('password', true),
        'email' => Request::getAsString('email', true),
        'role' => Request::getAsString('role', true),
    ];


    $user = User::build($fields);

    $result = $user->insert();

    if ($result) {

        $user = User::find($result);

        unset($user->password_hash);

        JSONResponse::validResponse(['user' => $user]);
        return;
    }


} catch (Exception $exception) {
    JSONResponse::exceptionResponse($exception);
}
