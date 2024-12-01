<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            /**
             * The relationships to be included.
             * Can be comma separated.
             * @var string
             * @example "tickets"
             */
            "include" => "string"
        ]);

        if ($this->include("tickets")) {
            return UserResource::collection(User::with('tickets')->paginate());
        }
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $request->validate([
            /**
             * The relationships to be included.
             * Can be comma separated.
             * @var string
             * @example "tickets"
             */
            "include" => "string"
        ]);

        if ($this->include("tickets")) {
            return new UserResource($user->load("tickets"));
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
