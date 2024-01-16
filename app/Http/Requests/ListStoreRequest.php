<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "title" => "required|string|max:191",
            "check" => [
                "nullable",
                Rule::in(["pendente", "concluida", "cancelada"]),
            ]
        ];
    }
}
