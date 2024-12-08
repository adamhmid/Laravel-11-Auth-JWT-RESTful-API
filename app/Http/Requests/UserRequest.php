<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseFormatTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
  use ApiResponseFormatTrait;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $rules = [
      'name'          => 'required|max:100',
      'email'         => 'required|email:rfc,dns|max:100',
      'password'      => 'nullable|max:50',
      'profile_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
    ];

    if ($this->isMethod('POST')) {
      $rules['email'] = 'required|email|unique:users,email|max:100';
      $rules['password'] = 'required|max:50';
    }

    return $rules;
  }

  /**
   * Get custom error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'name.required'       => 'The name field is required.',
      'name.max'            => 'The name field may not be greater than 100 characters.',
      'email.required'      => 'The email address field is required.',
      'email.email'         => 'The email address must be a valid email address',
      'email.max'           => 'The email address field may not be greater than 100 characters.',
      'email.unique'        => 'The email address has already been taken.',
      'password.required'   => 'The password field is required.',
      'password.max'        => 'The password field may not be greater than 50 characters.',
      'profile_image.image' => 'The profile image must be an image.',
      'profile_image.mimes' => 'The profile image must be a file of type: png, jpg, jpeg.',
      'profile_image.max'   => 'The profile image may not be greater than 2MB.',
    ];
  }

  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException($this->validationFailedResponse($validator->errors()));
  }
}
