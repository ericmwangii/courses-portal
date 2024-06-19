<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            ['name' => 'User One', 'email' => 'userone@example.com', 'password' => Hash::make('password')],
            ['name' => 'User Two', 'email' => 'usertwo@example.com', 'password' => Hash::make('password')],
            ['name' => 'User Three', 'email' => 'userthree@example.com', 'password' => Hash::make('password')],
            ['name' => 'User Four', 'email' => 'userfour@example.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
