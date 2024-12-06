<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $model = $request->mappedAttributes();
        return new TicketResource(Ticket::create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Ticket $ticket)
    {
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
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->isAble("update", $ticket);
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return $this->ok("Ticket successfully deleted.");
    }
}
