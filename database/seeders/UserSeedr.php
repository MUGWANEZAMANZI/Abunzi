<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeedr extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['citizen', 'justice', 'chief'];

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password123'),
                'identification' => 'ID' . str_pad($i, 14, '0', STR_PAD_LEFT),
                'role' => $roles[array_rand($roles)],
            ]);
        }
    }
}
