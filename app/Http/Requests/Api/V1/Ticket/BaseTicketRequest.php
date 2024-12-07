<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes()
    {
        $attrsMap = [
            "data.attributes.title" => "title",
            "data.attributes.description" => "description",
            "data.attributes.status" => "status",
            'data.relationships.author.data.id' => "user_id"
        ];

        $mappedAttrs = [];
        foreach ($attrsMap as $key => $colName) {
            if ($this->has($key)) {
                $mappedAttrs[$colName] = $this->input($key);
            }
        }

        return $mappedAttrs;
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
