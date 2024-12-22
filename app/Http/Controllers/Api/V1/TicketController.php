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
     * @queryParam include string The relationships to be included. Example: author
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
     * Get a Ticket
     *  
     * @group Managing Tickets
     * 
     * @queryParam include string The relationships to be included. Example: author
     * 
     * @return TicketResource
     */
    public function show(Request $request, Ticket $ticket): TicketResource
    {
        $this->isAble('view', $ticket);

        if ($this->include("author")) {
            return new TicketResource($ticket->load("author"));
        }

        return new TicketResource($ticket);
    }

    /**
     * Update a Ticket
     * 
     * Update the specified ticket. Users can only update their own tickets. Managers can update any ticket.
     * 
     * @group Managing Tickets
     * 
     * @return TicketResource
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->isAble("update", $ticket);
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Replace a Ticket
     * 
     * Replace the specified ticket. Managers only can replace tickets.
     * 
     * @group Managing Tickets
     * 
     * @return TicketResource
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->isAble('replace', $ticket);
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Delete a Ticket
     * 
     * Delete the specified ticket. Users can only delete their own tickets. Managers can delete any ticket.
     * 
     * @group Managing Tickets
     * 
     * @response status=200 scenario=success "Ticket successfully deleted."
     * @response status=404 scenario="Ticket not found" "Ticket cannot be found."
     */
    public function destroy(Ticket $ticket)
    {
        $this->isAble('delete', $ticket);
        $ticket->delete();
        return $this->ok("Ticket successfully deleted.");
    }
}
