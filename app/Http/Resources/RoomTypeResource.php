<?php

namespace App\Http\Resources;

use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    use TranslationTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roomType = [
            'id' => $this->id,
            'name' => $this->getAttributeTranslation('name') ?? null,
            'category' => $this->getAttributeTranslation('category') ?? null,
            'capacity' => $this->capacity,
            'image' => $this->image,
            'descraption' => $this->getAttributeTranslation('description') ?? null,
            'daily_price' => $this->daily_price,
            'monthly_price' => $this->monthly_price,
        ];

        return $roomType;
    }
}
