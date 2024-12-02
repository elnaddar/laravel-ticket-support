<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $author, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::where("user_id", $author->id)
                ->filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $author, StoreTicketRequest $request)
    {
        $model = [
            "title" => $request->input("data.attributes.title"),
            "description" => $request->input("data.attributes.description"),
            "status" => $request->input("data.attributes.status"),
            "user_id" => $author->id,
        ];

        return new TicketResource(Ticket::create($model));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {
                return $this->ok("Ticket successfully deleted.");
            }
            return $this->error("Ticket cannot be found.", 404);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }
}
