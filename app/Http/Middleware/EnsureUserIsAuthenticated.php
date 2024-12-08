<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseFormatTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
  use ApiResponseFormatTrait;

  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!Auth::user()) {
      return $this->unauthorizedResponse();
    }

    return $next($request);
  }
}
