<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'logo' => 'default.jpg',
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@emenu.com',
            'password' => bcrypt('khan'), // Change this to a secure password
            'role' => 'admin',
        ]);
    }
}
 