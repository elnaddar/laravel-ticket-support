<?php

namespace App\Http\Requests\Api\V1\Ticket;

use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            "data.attributes.title" => ["required", "string"],
            "data.attributes.description" => ["required", "string"],
            "data.attributes.status" => ["required", "string", "in:A,C,H,X"],
        ];

        if ($this->routeIs("tickets.replace")) {
            $rules['data.relationships.author.data.id'] = ["required", "exists:users,id"];
        }

        return $rules;
    }
}
