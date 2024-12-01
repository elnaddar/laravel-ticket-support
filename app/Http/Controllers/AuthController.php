<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\LoginUserRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;
    function login(LoginUserRequest $request)
    {
        return $this->ok($request->email);
    }
}
