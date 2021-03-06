<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ValidateOtpRequest class
 * @package App\Http\Requests
 *
 * @property string $identifier
 * @property string $token
 */
class ValidateOtpRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules(): array
  {
    return [
      'identifier' => 'required|string|max:255',
      'token' => 'required|string|max:255',
    ];
  }
}
