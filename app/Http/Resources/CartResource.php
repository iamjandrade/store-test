<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'completed_at' => optional($this->completed_at)->toDateTimeString(),
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'items_count' => $this->items->sum('quantity'),
            'subtotal' => $this->items->sum(function ($i) { return ($i->price ?? 0) * $i->quantity; }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
