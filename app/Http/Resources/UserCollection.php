<?php

namespace App\Http\Resources;

use App\Enums\ApiStatus;
use App\Enums\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @return array<int|string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'status'      => ApiStatus::SUCCESS,
      'status_code' => Response::HTTP_OK,
      'message'     => ResponseMessage::RETRIEVED_SUCCESSFULLY,
      'data'        => $this->collection
    ];
  }
}
