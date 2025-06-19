<?php

namespace App\Http\Resources;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'release_date' => $this->release_date,
            'duration' => $this->duration,
            'rating' => $this->rating,
            'language' => $this->language,
            // 'thumbnail_url' => $this->getFirstMediaUrl('preview','thumbnail'),
            'thumbnail_url' => $this->thumbnail_url,
            'trailer_url' => $this->trailer_url,
            'video_url' => $this->video_url,
            'genres' => $this->whenLoaded('genres', new GenreCollection($this->genres))
        ];
    }
}
