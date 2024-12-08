<?php

namespace App\Traits;

use App\Enums\ApiStatus;
use App\Enums\ResponseMessage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseFormatTrait
{
  /**
   * @param array $token
   * @return \Illuminate\Http\JsonResponse
   */
  private function loginResponse($token)
  {
    $data = [
      'token_type' => 'Bearer',
      'access_token' => $token['access_token'],
      'refresh_token' => $token['refresh_token']
    ];

    return $this->jsonResponse(ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::LOGIN_SUCCESSFUL, $data);
  }

  private function logoutResponse()
  {
    return $this->jsonResponse(ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::LOGGED_OUT_SUCCESSFULLY);
  }

  private function validationFailedResponse($errorDetails = null)
  {
    $response = [
      'status'      => ApiStatus::ERROR,
      'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
      'error'       => [
        'message'   => ResponseMessage::VALIDATION_FAILED,
        'timestamp' => Carbon::now(),
      ]
    ];

    if ($errorDetails !== null) {
      $response['error']['details'] = $errorDetails;
    }

    return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
  }

  /**
   * @param JsonResource $resource
   * @param string $actionName
   * @return \Illuminate\Http\JsonResponse
   */
  private function respondWithResource(JsonResource $resource = null, $actionName)
  {
    $actions = [
      'index'   => [ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::RETRIEVED_SUCCESSFULLY],
      'store'   => [ApiStatus::SUCCESS, Response::HTTP_CREATED, ResponseMessage::CREATED_SUCCESSFULLY],
      'show'    => [ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::FETCHED_SUCCESSFULLY],
      'update'  => [ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::UPDATED_SUCCESSFULLY],
      'destroy' => [ApiStatus::SUCCESS, Response::HTTP_OK, ResponseMessage::DELETED_SUCCESSFULLY]
    ];

    if (array_key_exists($actionName, $actions)) {
      return response()->json(
        [
          'status'      => $actions[$actionName][0],
          'status_code' => $actions[$actionName][1],
          'message'     => $actions[$actionName][2],
          'data'        => $resource
        ],
        $actions[$actionName][1]
      );
    }
  }

  private function unauthorizedResponse()
  {
    return $this->errorResponse(ApiStatus::ERROR, Response::HTTP_UNAUTHORIZED, ResponseMessage::UNAUTHORIZED);
  }

  private function forbiddenResponse()
  {
    return $this->errorResponse(ApiStatus::ERROR, Response::HTTP_FORBIDDEN, ResponseMessage::FORBIDDEN);
  }

  public static function unauthorizedResponseException()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_UNAUTHORIZED, ResponseMessage::UNAUTHORIZED);
  }

  public static function forbiddenResponseException()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_FORBIDDEN, ResponseMessage::FORBIDDEN);
  }

  public static function notFoundResponse()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_NOT_FOUND, ResponseMessage::RESOURCE_NOT_FOUND);
  }

  public static function methodNotAllowedResponse()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_METHOD_NOT_ALLOWED, ResponseMessage::METHOD_NOT_ALLOWED_MSG);
  }

  public static function notAcceptableResponse()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_NOT_ACCEPTABLE, ResponseMessage::NOT_ACCEPTABLE_MSG);
  }

  public static function tooManyRequestsResponse()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_TOO_MANY_REQUESTS, ResponseMessage::TOO_MANY_REQUESTS_MSG);
  }

  public static function internalServerErrorResponse()
  {
    return self::errorResponseException(ApiStatus::ERROR, Response::HTTP_INTERNAL_SERVER_ERROR, ResponseMessage::INTERNAL_SERVER_ERROR_MESSAGE);
  }

  public static function recordException($e)
  {
    Log::error($e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine());
  }

  public static function errorResponseException($status, $statusCode, $message, $errorDetails = null)
  {
    $response = [
      'status'      => $status,
      'status_code' => $statusCode,
      'error'       => [
        'message'   => $message,
        'timestamp' => Carbon::now(),
      ]
    ];

    if ($errorDetails !== null) {
      $response['error']['details'] = $errorDetails;
    }

    return response()->json($response, $statusCode);
  }

  private function jsonResponse($status, $statusCode, $message, $data = null)
  {
    $response = [
      'status'      => $status,
      'status_code' => $statusCode,
      'message'     => $message,
    ];

    if ($data !== null) {
      $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
  }

  private function errorResponse($status, $statusCode, $message, $errorDetails = null)
  {
    $response = [
      'status'      => $status,
      'status_code' => $statusCode,
      'error'       => [
        'message'   => $message,
        'timestamp' => Carbon::now(),
      ]
    ];

    if ($errorDetails !== null) {
      $response['error']['details'] = $errorDetails;
    }

    return response()->json($response, $statusCode);
  }
}
