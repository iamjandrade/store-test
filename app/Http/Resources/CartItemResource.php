<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product?->id,
                'name' => $this->product?->name,
                'price' => $this->product?->price,
            ],
            'quantity' => $this->quantity,
            'price_snapshot' => $this->price,
            'total' => $this->when(isset($this->price), $this->price * $this->quantity),
        ];
    }
}
