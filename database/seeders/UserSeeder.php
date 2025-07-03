<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Dummy User',
            'email' => 'dummy@example.com',
            'password' => Hash::make('password'), // jangan lupa hashing
        ]);

        User::factory()->count(10)->create(); // buat 10 dummy user pakai factory
    }
}
