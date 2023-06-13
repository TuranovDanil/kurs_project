<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'name' => $this->name,
            'surname' => $this->surname,
            'telephone' => $this->telephone,
            'role' => $this->role,
            'products' => ProductProviderResource::collection($this->products)
        ];
    }
}
