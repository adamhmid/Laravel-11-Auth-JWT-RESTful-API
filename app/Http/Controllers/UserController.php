<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller implements HasMiddleware
{
  use ApiResponseFormatTrait;

  public static function middleware(): array
  {
    return [
      new Middleware('permission:create user', only: ['store']),
      new Middleware('permission:read user', only: ['index', 'show']),
      new Middleware('permission:update user', only: ['update']),
      new Middleware('permission:delete user', only: ['destroy']),
    ];
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $users = User::applyQueryParameters($request);

    return (new UserCollection($users))->response()->setStatusCode(200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(UserRequest $request)
  {
    $data = $request->validated();
    $data['password'] = Hash::make($data['password']);

    if ($request->hasFile('profile_image')) {
      $file = $request->file('profile_image')->store('profile_images', 'public');
      $imagePath = str_replace('profile_images/', '', $file);
      $data['profile_image'] = $imagePath;
    }

    $user = User::create($data);

    return $this->respondWithResource(new UserResource($user), 'store');
  }

  /**
   * Display the specified resource.
   */
  public function show(int $id)
  {
    $user = User::findOrFail($id);

    return $this->respondWithResource(new UserResource($user), 'show');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UserRequest $request, int $id)
  {
    $data = $request->validated();

    if (isset($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    }

    $user = User::findOrFail($id);

    if ($request->hasFile('profile_image')) {
      if ($user->profile_image) {
        // Delete old profile image in storage
        Storage::disk('public')->delete($user->profile_image);
      }

      $file = $request->file('profile_image')->store('profile_images', 'public');
      $imagePath = str_replace('profile_images/', '', $file);
      $data['profile_image'] = $imagePath;
    }

    $user->update($data);

    return $this->respondWithResource(new UserResource($user), 'update');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(int $id)
  {
    $user = User::findOrFail($id);

    if ($user->profile_image) {
      // Delete profile image in storage
      Storage::disk('public')->delete($user->profile_image);
    }

    $user->delete();

    return $this->respondWithResource(null, 'destroy');
  }
}
