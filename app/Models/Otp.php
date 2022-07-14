<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $code
 * @property string $clear_code Code without hashing (used only in factory)
 * @see OtpFactory::definition()
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class Otp extends Model
{
  use HasFactory;

  protected $casts = [
    'active' => 'boolean'
  ];

  protected $fillable = [
    'code',
    'active'
  ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($post) {
      $post->{$post->getKeyName()} = (string)Str::uuid();
    });
  }

  public function getIncrementing()
  {
    return false;
  }

  public function getKeyType()
  {
    return 'string';
  }

  /**
   * Interact with the OTP code.
   *
   * @return Attribute
   */
  protected function code(): Attribute
  {
    return Attribute::make(
      get: fn($value) => $value,
      set: fn($value) => Hash::make($value),
    );
  }
}
