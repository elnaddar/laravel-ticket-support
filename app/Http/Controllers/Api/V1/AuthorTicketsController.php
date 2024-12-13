<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\Ticket\StoreTicketRequest;
use App\Http\Requests\Api\V1\Ticket\UpdateTicketRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Api\V1\Ticket\ReplaceTicketRequest;

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
    public function store(StoreTicketRequest $request, User $author)
    {
        $this->isAble('create', Ticket::class);
        $model = $request->mappedAttributes(["author" => "user_id"]);
        return new TicketResource(Ticket::create($model));
    }

    private function alter(Request $request, $ability,  $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble($ability, $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        return $this->alter($request, "update", $author_id, $ticket_id);
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        return $this->alter($request, "replace", $author_id, $ticket_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();
            $ticket->delete();
            return $this->ok("Ticket successfully deleted.");
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }
}
