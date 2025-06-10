<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email'=>$this->user->email,
            'age'=>$this->age,
            'is_kid' => $this->is_kid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
