<?php

namespace App\Http\Resources;

use App\Enums\ApiStatus;
use App\Enums\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'status'      => ApiStatus::SUCCESS,
      'status_code' => Response::HTTP_OK,
      'message'     => ResponseMessage::TOKEN_CREATED,
      'data' => [
        'token_type'   => 'Bearer',
        'access_token' => $this->resource
      ]
    ];
  }
}
