<?php

namespace App\Auth\Guards;

use App\Services\JWTAuthService;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JWTGuard implements Guard
{
  use GuardHelpers, ApiResponseFormatTrait;

  protected $jwtAuthService;
  protected $request;
  protected $provider;
  protected $user;

  public function __construct(JWTAuthService $jwtAuthService, Request $request, UserProvider $provider)
  {
    $this->jwtAuthService = $jwtAuthService;
    $this->request = $request;
    $this->provider = $provider;
  }

  /**
   * Attempt to authenticate the user by their credentials.
   *
   * @param  array  $credentials
   * @return bool
   */
  public function attempt(array $credentials = [])
  {
    $user = $this->provider->retrieveByCredentials($credentials);

    if ($user && Hash::check($credentials['password'], $user->password)) {
      // Set pengguna yang terautentikasi
      $this->user = $user;

      return true;
    }

    return false;
  }

  /**
   * Check if the user is authenticated.
   *
   * @return bool
   */
  public function check()
  {
    return !is_null($this->user());
  }

  /**
   * Get the currently authenticated user.
   *
   * @return mixed
   */
  public function user()
  {
    if ($this->user) {
      return $this->user;
    }

    $token = $this->request->cookie('access_token');

    if ($token) {
      try {
        $decoded = $this->jwtAuthService->decodeToken($token);
        $this->user = $this->provider->retrieveById($decoded->sub);
      } catch (\Exception $e) {
        return null;
      }
    }

    return $this->user;
  }

  /**
   * Validate the user's credentials.
   *
   * @param  array  $credentials
   * @return bool
   */
  public function validate(array $credentials = [])
  {
    return $this->attempt($credentials);
  }
}
