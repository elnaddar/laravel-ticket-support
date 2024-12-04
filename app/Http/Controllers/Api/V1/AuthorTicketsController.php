<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
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
        $model = $request->mappedAttributes();
        $model["user_id"] = $author->id;
        return new TicketResource(Ticket::create($model));
    }

    private function updateOrReplace($request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

            return $this->error("Ticket cannot be found.", 404);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        return $this->updateOrReplace($request, $author_id, $ticket_id);
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        return $this->updateOrReplace($request, $author_id, $ticket_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {
                $ticket->delete();
                return $this->ok("Ticket successfully deleted.");
            }
            return $this->error("Ticket cannot be found.", 404);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }
}
