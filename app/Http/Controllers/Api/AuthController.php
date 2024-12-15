<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginUserRequest;
use App\Permissions\V1\Abilities;

class AuthController extends Controller
{
    use ApiResponses;
    /**
    * Login
    *
    * Authenticates the user and returns the user's API token.
    *
    * @unauthenticated
    * @group Authentication
    * @response 200 {
        "message": "Authenticated",
        "status": 200,
        "data": {
            "token": "{YOUR_AUTH_KEY}"
        }
    }
    */
    function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only("email", "password"))) {
            return $this->error("Invalid Credentials!", 401);
        }

        $user = User::firstWhere("email", $request->email);

        return $this->ok("Authenticated", [
            "token" => $user->createToken(
                "API token for " . $user->email, // token name
                Abilities::getAbilities($user), // abilities
                now()->addMonth() // expiration date
            )->plainTextToken,
        ]);
    }

    /**
     * Logout
     *
     * Logouts the user and removes the API key.
     *
     * @group Authentication
     * @response 200 {}
     */
    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok("");
    }
}
