<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\DeprecatedUuidMethodsTrait;

class Otp extends Model
{
    use HasFactory;

    protected $casts = [
      'active' => 'boolean'
    ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($post) {
      $post->{$post->getKeyName()} = (string) Str::uuid();
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
}
