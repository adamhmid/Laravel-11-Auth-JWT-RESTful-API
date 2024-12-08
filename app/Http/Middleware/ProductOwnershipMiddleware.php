<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Traits\ApiResponseFormatTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductOwnershipMiddleware
{
  use ApiResponseFormatTrait;

  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, $function): Response
  {
    if ($function === 'index') {
      // Jika pengguna adalah superadmin atau buyer, tampilkan semua produk
      if (Auth::user()->hasRole(['superadmin', 'buyer'])) {
        $request->merge(['products' => Product::applyQueryParameters($request)]);

        return $next($request);
      }

      // Jika pengguna adalah seller, tampilkan hanya produk miliknya
      $request->merge(['products' => Product::applyQueryParameters($request, Auth::id())]);

      return $next($request);
    }

    if ($function === 'store') {
      if (Auth::user()->hasRole('seller')) {
        return $next($request);
      }
    }

    if ($function === 'show' || $function === 'update' || $function === 'destroy') {
      $product = Product::findOrFail($request->route('product'));

      if (Auth::user()->hasRole('superadmin') || Auth::id() === $product->user_id) {
        // Menambahkan produk ke request
        $request->merge(['product' => $product]);

        return $next($request);
      }
    }

    return $this->forbiddenResponse();
  }
}
