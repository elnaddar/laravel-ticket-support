<?php

namespace App\Http\Requests\Api\V1\Ticket;

class ReplaceTicketRequest extends BaseTicketRequest
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
        $authorTicketsReplaceRoute = $this->routeIs("authors.tickets.replace");
        $author_key = $authorTicketsReplaceRoute ? "author" : "data.relationships.author.data.id";

        $rules = [
            "data" => ["required", "array"],
            "data.attributes" => ["required", "array"],
            "data.attributes.title" => ["required", "string"],
            "data.attributes.description" => ["required", "string"],
            "data.attributes.status" => ["required", "string", "in:A,C,H,X"],
        ];
        
        if(!$authorTicketsReplaceRoute){
            $rules["data.relationships"] = ["required", "array"];
            $rules["data.relationships.author"] = ["required", "array"];
            $rules["data.relationships.author.data"] = ["required", "array"];
        }

        $rules[$author_key] = ["required", "exists:users,id"];

        return $rules;
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function bodyParameters(): array
    {
        $documentations = [
            'data.attributes.title' => [
                'description' => 'The title of the ticket.',
                'example' => 'Fix the bug',
                'required' => false
            ],
            'data.attributes.description' => [
                'description' => 'The description of the ticket.',
                'example' => 'The bug is causing the application to crash.',
                'required' => false
            ],
            'data.attributes.status' => [
                'description' => 'The status of the ticket. A: Active, C: Closed, H: On Hold, X: Canceled.',
                'example' => 'A',
                'required' => false,
                'enum' => ['A', 'C', 'H', 'X']
            ],
        ];

        if ($this->routeIs("authors.tickets.update")) {
            $documentations["author"] = [
                'description' => 'The ID of the author of the ticket.',
                'example' => '1',
                'required' => false
            ];
        } else {
            $documentations["data.relationships.author.data.id"] = [
                'description' => 'The ID of the author of the ticket.',
                'example' => '1',
                'required' => false
            ];
        }

        return $documentations;
    }

}
