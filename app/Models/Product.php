<?php

namespace App\Models;

use App\Traits\QueryParameterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model
{
  use HasFactory, HasRoles, QueryParameterTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'product_image',
    'price',
    'user_id',
  ];

  /**
   * The attributes that should be searched.
   *
   * @var array<int, string>
   */
  protected static $searchable = ['name', 'price'];

  // Many-to-One Relationship: Many products belong to one user
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
