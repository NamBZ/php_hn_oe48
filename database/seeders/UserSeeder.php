<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'phone' => '0123456789',
            'role' => UserRole::ADMIN,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user'),
            'remember_token' => Str::random(10),
            'phone' => '0123456789',
            'role' => UserRole::USER,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'User2',
            'email' => 'user2@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user2'),
            'remember_token' => Str::random(10),
            'phone' => '0123456789',
            'role' => UserRole::USER,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
