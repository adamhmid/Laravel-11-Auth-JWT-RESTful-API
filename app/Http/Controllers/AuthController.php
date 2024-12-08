<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AccessTokenResource;
use App\Models\User;
use App\Services\JWTAuthService;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
  use ApiResponseFormatTrait;

  protected $jwtAuthService;

  public function __construct(JWTAuthService $jwtAuthService)
  {
    $this->jwtAuthService = $jwtAuthService;
  }

  public function login(LoginRequest $request)
  {
    $credentials = $request->validated();

    if (!Auth::attempt($credentials)) {
      return $this->unauthorizedResponse();
    }

    $user = Auth::user()->load('roles');
    $userData = [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'role' => $user->roles->pluck('name')->first(),
    ];

    $accessToken = $this->jwtAuthService->generateToken($userData);
    $refreshToken = $this->jwtAuthService->generateToken($userData, true);
    $user->update(['refresh_token' => $refreshToken]);

    $token = [
      'access_token' => $accessToken,
      'refresh_token' => $refreshToken,
    ];

    return $this->loginResponse($token)
      // Parameter: (nama, nilai, waktu kadaluarsa (dalam menit), path, domain, secure, httpOnly, raw, sameSite)
      ->cookie('access_token', $accessToken, 60, '/', env('COOKIE_DOMAIN'), false, true, false, 'Strict') // 1 jam
      ->cookie('refresh_token', $refreshToken, 43800, '/', env('COOKIE_DOMAIN'), false, true, false, 'Strict'); // 31 hari
  }

  public function refresh(Request $request)
  {
    $refreshToken = $request->cookie('refresh_token');

    if (!$refreshToken) {
      return $this->unauthorizedResponse();
    }

    $decoded = $this->jwtAuthService->decodeToken($refreshToken);

    if (!$decoded) {
      return $this->unauthorizedResponse();
    }

    $user = User::findOrFail($decoded->sub);

    if ($refreshToken !== $user->refresh_token) {
      return $this->unauthorizedResponse();
    }

    $newAccessToken = $this->jwtAuthService->generateToken($user);

    return response(new AccessTokenResource($newAccessToken))
      ->cookie('access_token', $newAccessToken, 60, '/', env('COOKIE_DOMAIN'), false, true, false, 'Strict'); // 1 jam
  }

  public function logout()
  {
    $id = Auth::id();

    User::where('id', $id)->update(['refresh_token' => null]);

    $accessToken = Cookie::forget('access_token');
    $refreshToken = Cookie::forget('refresh_token');

    return $this->logoutResponse()->withCookie($accessToken)->withCookie($refreshToken);
  }
}
