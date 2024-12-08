<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Super Admin
    $superadmin = User::create([
      'name'              => 'Super Admin',
      'email'             => 'superadmin@gmail.com',
      'password'          => Hash::make('qwert123'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $superadmin->assignRole('superadmin');

    // Seller
    $seller = User::create([
      'name'              => 'Seller',
      'email'             => 'seller@gmail.com',
      'password'          => Hash::make('qwert123'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $seller->assignRole('seller');

    // Buyer
    $buyer = User::create([
      'name'              => 'Buyer',
      'email'             => 'buyer@gmail.com',
      'password'          => Hash::make('qwert123'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $buyer->assignRole('buyer');
  }
}
