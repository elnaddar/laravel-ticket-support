<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\V1\AuthorResource;
use App\Http\Requests\Api\V1\User\StoreUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;

/**
 * @hideFromAPIDocumentation
 */
class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return AuthorResource::collection(
            User::select("users.*")
                ->join("tickets", "users.id", "=", "tickets.user_id")
                ->distinct()
                ->filter($filters)
                ->paginate()
        );
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
    public function show(Request $request, User $author)
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
            return new AuthorResource($author->load("tickets"));
        }
        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author)
    {
        //
    }
}
