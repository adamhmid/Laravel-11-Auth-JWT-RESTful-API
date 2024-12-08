<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  use ApiResponseFormatTrait;

  /**
   * Display the specified resource.
   */
  public function show()
  {
    $user = Auth::user();

    return $this->respondWithResource(new UserResource($user), 'show');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateProfileRequest $request)
  {
    $data = $request->validated();

    if (isset($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    }

    $user = Auth::user();

    if ($request->hasFile('profile_image')) {
      if ($user->profile_image) {
        // Delete old image in storage
        Storage::disk('public')->delete($user->profile_image);
      }

      $file = $request->file('profile_image')->store('profile_images', 'public');
      $imagePath = str_replace('profile_images/', '', $file);
      $data['profile_image'] = $imagePath;
    }

    $user->update($data);

    return $this->respondWithResource(new UserResource($user), 'update');
  }
}
