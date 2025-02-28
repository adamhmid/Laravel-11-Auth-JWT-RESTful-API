<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseFormatTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
    return [
      'email'    => 'required|email:rfc,dns|max:100',
      'password' => 'required|max:50',
    ];
  }

  /**
   * Get custom error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'email.required'    => 'The email address field is required.',
      'email.email'       => 'The email address must be a valid email address',
      'email.max'         => 'The email address field may not be greater than 100 characters.',
      'password.required' => 'The password field is required.',
      'password.max'      => 'The password field may not be greater than 50 characters.',
    ];
  }

  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException($this->validationFailedResponse($validator->errors()));
  }
}
