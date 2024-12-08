<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Super Admin
    $superadmin = Role::create(['name' => 'superadmin']);
    $superadmin->givePermissionTo([
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
    ]);

    // Seller
    $seller = Role::create(['name' => 'seller']);
    $seller->givePermissionTo([
      'create product',
      'read product',
      'update product',
      'delete product',
    ]);

    // Buyer
    $buyer = Role::create(['name' => 'buyer']);
    $buyer->givePermissionTo([
      'read product',
    ]);
  }
}
