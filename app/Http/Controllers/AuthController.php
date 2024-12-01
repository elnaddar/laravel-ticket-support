<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;
    function login(ApiLoginRequest $request)
    {
        return $this->ok($request->email);
    }
}
