<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

trait QueryParameterTrait
{
  /**
   * Apply query parameters for pagination, filtering, sorting, and searching.
   *
   * @param Request $request
   * @return \Illuminate\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection
   */
  public static function applyQueryParameters(Request $request, $userId = null): Paginator
  {
    $query = self::query();
    $searchableColumns = static::$searchable ?? [];

    // Filter by user (if $userId is provided)
    if ($userId) {
      $query->where('user_id', $userId);
    }

    // Paginasi
    $page = $request->input('page', 1);
    $limit = $request->input('limit', 10);

    // Filter
    $filters = $request->query('filters', []);
    foreach ($filters as $field => $value) {
      if (!empty($value)) {
        $query->where($field, 'LIKE', "%{$value}%");
      }
    }

    // Pencarian
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search, $searchableColumns) {
        foreach ($searchableColumns as $column) {
          $q->orWhere($column, 'LIKE', "%{$search}%");
        }
      });
    }

    // Sortir
    $sortField = $request->input('sort_by', 'id');
    $sortOrder = $request->input('sort_order', 'asc');
    $query->orderBy($sortField, $sortOrder);

    // Return paginated query
    return $query->paginate($limit, ['*'], 'page', $page);
  }
}
