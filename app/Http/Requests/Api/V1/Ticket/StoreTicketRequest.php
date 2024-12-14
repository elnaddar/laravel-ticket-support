<?php

namespace App\Http\Requests\Api\V1\Ticket;

use App\Enums\V1\Abilities\TicketAbility;
use Faker\Provider\Base;
use Illuminate\Foundation\Http\FormRequest;

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
        $author_key = $this->routeIs("authors.tickets.store") ? "author" : "data.relationships.author.data.id";
        $user = $this->user();

        $rules = [
            "data.attributes.title" => ["required", "string"],
            "data.attributes.description" => ["required", "string"],
            "data.attributes.status" => ["required", "string", "in:A,C,H,X"],
            $author_key => ["required", "integer", "exists:users,id", "size:$user->id"],
        ];

        if ($user->tokenAble(TicketAbility::create)) {
            array_pop($rules[$author_key]);
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs("authors.tickets.store")) {
            $this->merge([
                'author' => $this->route("author")->id
            ]);
        }
    }
}
