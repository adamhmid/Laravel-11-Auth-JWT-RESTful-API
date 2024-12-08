<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller implements HasMiddleware
{
  use ApiResponseFormatTrait;

  public static function middleware(): array
  {
    return [
      new Middleware('product:index', only: ['index']),
      new Middleware('product:store', only: ['store']),
      new Middleware('product:show', only: ['show']),
      new Middleware('product:update', only: ['update']),
      new Middleware('product:destroy', only: ['destroy']),
    ];
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $products = $request->products;

    return (new ProductCollection($products))->response()->setStatusCode(200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ProductRequest $request)
  {
    $data = $request->validated();
    $data['user_id'] = Auth::id();

    if ($request->hasFile('product_image')) {
      $file = $request->file('product_image')->store('product_images', 'public');
      $imagePath = str_replace('product_images/', '', $file);
      $data['product_image'] = $imagePath;
    }

    $product = Product::create($data);

    return $this->respondWithResource(new ProductResource($product), 'store');
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $product = $request->product;

    return $this->respondWithResource(new ProductResource($product), 'show');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ProductRequest $request, string $id)
  {
    $data = $request->validated();
    $data['user_id'] = Auth::id();
    $product = $request->product;

    if ($request->hasFile('product_image')) {
      if ($product->product_image) {
        // Delete old product image in storage
        Storage::disk('public')->delete($product->product_image);
      }

      $file = $request->file('product_image')->store('product_images', 'public');
      $imagePath = str_replace('product_images/', '', $file);
      $data['product_image'] = $imagePath;
    }

    $product->update($data);

    return $this->respondWithResource(new ProductResource($product), 'update');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    $product = $request->product;

    if ($product->product_image) {
      // Delete product image in storage
      Storage::disk('public')->delete($product->product_image);
    }

    $product->delete();

    return $this->respondWithResource(null, 'destroy');
  }
}
