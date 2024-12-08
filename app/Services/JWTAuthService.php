<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuthService
{
  /**
   * Generate JWT Token
   *
   * @param  array  $userData
   * @return string
   */
  public function generateToken($userData, $isRefreshToken = false)
  {
    $issuedAt = time();
    $expirationTime = $issuedAt + ($isRefreshToken ? (30 * 24 * 60 * 60) : 3600); // Refresh token valid 1 bulan (dalam detik), Access token valid 1 jam (dalam detik)
    $payload = [
      'iat' => $issuedAt,
      'exp' => $expirationTime,
      'sub' => $userData['id'],
      'name' => $userData['name'],
      'email' => $userData['email'],
      'role' => $userData['role'],
    ];

    return JWT::encode($payload, env('JWT_SECRET'), env('JWT_ALGO'));
  }

  /**
   * Decode JWT Token
   *
   * @param  string  $token
   * @return object
   */
  public function decodeToken($token)
  {
    try {
      return JWT::decode($token, new Key(env('JWT_SECRET'), env('JWT_ALGO')));
    } catch (\Exception $e) {
      return null;
    }
  }
}
