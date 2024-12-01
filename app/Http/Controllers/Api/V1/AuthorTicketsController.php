<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorTicketsController extends Controller
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
}
