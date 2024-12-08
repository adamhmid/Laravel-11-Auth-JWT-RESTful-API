<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id'            => $this->id,
      'name'          => $this->name,
      'product_image' => $this->product_image,
      'price'         => $this->price,
      'user_id'       => $this->user_id,
      'created_at'    => $this->created_at->format('d M Y h:i A'),
      'updated_at'    => $this->updated_at->format('d M Y h:i A'),
    ];
  }
}
