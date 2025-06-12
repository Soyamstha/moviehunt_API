<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'rating'=>$this->rating,
            'review'=>$this->review,
            'profile_name' => $this->whenLoaded('profile', function(){
                return $this->profile->name;
            })
        ];
    }
}
