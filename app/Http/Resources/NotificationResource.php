<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $notification = [
            'id' => $this->id,
            'data' => $this->data,
            'read' => $this->read_at ? 'yes' : 'no',
            'created_at' => $this->created_at,
        ];

        return $notification;
    }
}
