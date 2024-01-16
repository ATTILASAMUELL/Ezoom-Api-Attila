<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_filter([
            "id" => $this->id,
            "title" => $this->title,
            "check" => $this->check,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ], function ($value) {
            return $value !== null;
        });
    }
}
