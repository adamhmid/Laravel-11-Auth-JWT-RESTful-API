<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = [
      'create permission',
      'read permission',
      'update permission',
      'delete permission',
      'create role',
      'read role',
      'update role',
      'delete role',
      'create user',
      'read user',
      'update user',
      'delete user',
      'create product',
      'read product',
      'update product',
      'delete product',
    ];

    foreach ($permissions as $permission) {
      Permission::create(['name' => $permission]);
    }
  }
}
