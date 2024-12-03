<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceTicketRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            "data.attributes.status" => [
                "in" => "The selected data.attributes.status is invalid. It MUST be in A, C, H, X."
            ],
            "data.relationships.author.data.id" => [
                "exists" => "The selected data.relationships.author.data.id is not found."
            ]
        ];
    }
}