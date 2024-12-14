<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $room = [
            'id' => $this->id,
            'room_type_id' => $this->room_type_id,
            'floor' => $this->floor,
            'number' => $this->number,
            'view' => $this->getAttributeTranslation('view') ?? null,
            'image' => Storage::url($this->image),
            'description' => $this->getAttributeTranslation('description') ?? null,
        ];

        return $room;
    }
}
