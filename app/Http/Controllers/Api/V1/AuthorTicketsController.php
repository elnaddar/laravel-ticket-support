<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\Ticket\StoreTicketRequest;
use App\Http\Requests\Api\V1\Ticket\UpdateTicketRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Api\V1\Ticket\ReplaceTicketRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketsController extends ApiController
{
    /**
     * Get All Tickets for an Author
     * 
     * @group Author Tickets
     * 
     * @queryParam sort string Sort the tickets by a specific field(s). Separate by comma. Denote desc with a minus sign. Example: title,-createdAt
     * @queryParam filter[status] string Filter tickets by status code: A, C, H, X. Separate by comma. Example: A,C
     * @queryParam filter[title] string Filter tickets by title. Wildcard supported. Example: *fix*
     * @queryParam filter[createdAt] string Filter tickets by creation date. Can be a single date or a range separated by comma. Example: 2023-01-01,2023-01-31
     * @queryParam filter[updatedAt] string Filter tickets by update date. Can be a single date or a range separated by comma. Example: 2023-01-01,2023-01-31
     * @queryParam include string The relationships to be included. Example: author
     * 
     * @return AnonymousResourceCollection
     */
    public function index(User $author, TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(
            Ticket::where("user_id", $author->id)
                ->filter($filters)->paginate()
        );
    }

    /**
     * Store a Ticket for an Author
     * 
     * @group Author Tickets
     * 
     * @return TicketResource
     */
    public function store(StoreTicketRequest $request, User $author): TicketResource
    {
        $this->isAble('create', Ticket::class);
        $model = $request->mappedAttributes(["author" => "user_id"]);
        return new TicketResource(Ticket::create($model));
    }
    /**
     * Alter the specified resource in storage.
     * @param Request $request
     * @param string $ability
     * @param int $author_id
     * @param int $ticket_id
     * @return TicketResource|<missing>
     */
    private function alter(Request $request, string $ability, int $author_id, int $ticket_id): TicketResource|JsonResponse
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble($ability, $ticket);

            $ticket->update($request->mappedAttributes(["author" => "user_id"]));
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket cannot be found.", 404);
        }
    }

    /**
     * Update a Ticket for an Author
     * 
     * Update the specified ticket. Users can only update their own tickets. Managers can update any ticket.
     * 
     * @group Author Tickets
     * 
     * @return TicketResource|JsonResponse
     */
    public function update(UpdateTicketRequest $request, int $author_id, int $ticket_id): TicketResource|JsonResponse
    {
        return $this->alter($request, "update", $author_id, $ticket_id);
    }

    /**
     * Replace a Ticket for an Author
     * 
     * Replace the specified ticket. Managers only can replace tickets.
     * 
     * @group Author Tickets
     * 
     * @return TicketResource|JsonResponse
     */
    public function replace(ReplaceTicketRequest $request, int $author_id, int $ticket_id): TicketResource|JsonResponse
    {
        return $this->alter($request, "replace", $author_id, $ticket_id);
    }

    /**
     * Delete a Ticket for an Author
     * 
     * Delete the specified ticket. Users can only delete their own tickets. Managers can delete any ticket.
     * 
     * @group Author Tickets
     * 
     * @return JsonResponse
     */
    public function destroy(int $author_id, int $ticket_id): JsonResponse
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
