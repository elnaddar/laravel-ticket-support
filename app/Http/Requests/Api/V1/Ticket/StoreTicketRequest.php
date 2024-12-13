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
        $rules = [
            "data.attributes.title" => ["required", "string"],
            "data.attributes.description" => ["required", "string"],
            "data.attributes.status" => ["required", "string", "in:A,C,H,X"],
            "data.relationships.author.data.id" => ["required", "integer", "exists:users,id"],
        ];

        $user = $this->user();
        $author_key = "data.relationships.author.data.id";

        if ($user->tokenAble(TicketAbility::createOwn)) {
            $rules[$author_key] = array_merge($rules[$author_key], ["size:$user->id"]);
        }
        return $rules;
    }
}

