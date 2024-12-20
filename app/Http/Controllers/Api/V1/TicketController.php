<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\Ticket\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\Ticket\StoreTicketRequest;
use App\Http\Requests\Api\V1\Ticket\UpdateTicketRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends ApiController
{
    /**
     * Get All Tickets
     * 
     * @group Managing Tickets
     * @queryParam sort string Sort the tickets by a specific field(s). Separate by comma. Denote desc with a minus sign. Example: title,-createdAt
     * @queryParam filter[status] string Filter tickets by status code: A, C, H, X. Separate by comma. Example: A,C
     * @queryParam filter[title] string Filter tickets by title. Wildcard supported. Example: *fix*
     * @queryParam filter[createdAt] string Filter tickets by creation date. Can be a single date or a range separated by comma. Example: 2023-01-01,2023-01-31
     * @queryParam filter[updatedAt] string Filter tickets by update date. Can be a single date or a range separated by comma. Example: 2023-01-01,2023-01-31
     * 
     * @return AnonymousResourceCollection
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        $this->isAble('viewAny', Ticket::class);
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a Ticket
     * 
     * Stores a new ticket. Users can only create tickets for themselves. Managers can create tickets for any user. 
     * 
     * @group Managing Tickets
     * 
     * @return TicketResource
     */
    public function store(StoreTicketRequest $request): TicketResource
    {
        $this->isAble('create', Ticket::class);
        $model = $request->mappedAttributes();
        return new TicketResource(Ticket::create($model));
    }

    /**
     * Display the specified resource.
     * @return TicketResource
     */
    public function show(Request $request, Ticket $ticket): TicketResource
    {
        $this->isAble('view', $ticket);

        $request->validate([
            /**
             * The relationships to be included.
             * Can be comma separated.
             * @var string
             * @example "author"
             */
            "include" => "string"
        ]);

        if ($this->include("author")) {
            return new TicketResource($ticket->load("author"));
        }

        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     * @return TicketResource
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->isAble("update", $ticket);
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Replace the specified resource in storage.
     * @return TicketResource
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->isAble('replace', $ticket);
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->isAble('delete', $ticket);
        $ticket->delete();
        return $this->ok("Ticket successfully deleted.");
    }
}
