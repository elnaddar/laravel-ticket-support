<?php

namespace App\Http\Requests\Api\V1\Ticket;

use App\Enums\V1\Abilities\TicketAbility;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authorTicketsStoreRoute = $this->routeIs("authors.tickets.store");
        $author_key = $authorTicketsStoreRoute ? "author" : "data.relationships.author.data.id";
        $user = Auth::user();

        $rules = [
            "data" => ["required", "array"],
            "data.attributes" => ["required", "array"],
            "data.attributes.title" => ["required", "string"],
            "data.attributes.description" => ["required", "string"],
            "data.attributes.status" => ["required", "string", "in:A,C,H,X"],
        ];
        
        if(!$authorTicketsStoreRoute){
            $rules["data.relationships"] = ["required", "array"];
            $rules["data.relationships.author"] = ["required", "array"];
            $rules["data.relationships.author.data"] = ["required", "array"];
        }

        $rules[$author_key] = ["required", "integer", "exists:users,id", "size:$user->id"];

        if ($user->tokenAble(TicketAbility::create)) {
            array_pop($rules[$author_key]);
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if ($this->routeIs("authors.tickets.store")) {
            $this->merge([
                'author' => $this->route("author")->id
            ]);
        }
    }

    public function bodyParameters(): array
    {
        $documentations = [
            'data.attributes.title' => [
                'description' => 'The title of the ticket.',
                'example' => 'Fix the bug',
            ],
            'data.attributes.description' => [
                'description' => 'The description of the ticket.',
                'example' => 'The bug is causing the application to crash.',
            ],
            'data.attributes.status' => [
                'description' => 'The status of the ticket. A: Active, C: Closed, H: On Hold, X: Canceled.',
                'example' => 'A',
                'enum' => ['A', 'C', 'H', 'X'],
            ],
        ];

        if($this->routeIs("authors.tickets.store")){
            $documentations['author'] = [
                'description' => 'The ID of the author of the ticket.',
                'example' => 1,
            ];
        } else {
            $documentations['data.relationships.author.data.id'] = [
                'description' => 'The ID of the author of the ticket.',
                'example' => 1,
            ];
        }

        return $documentations; 
    }
}
