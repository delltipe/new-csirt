<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin user already exists
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
            ]);
            echo "Admin user created: admin@gmail.com / 12345678\n";
        } else {
            echo "Admin user already exists.\n";
            // Update is_admin flag just in case
            User::where('email', 'admin@gmail.com')->update(['is_admin' => true]);
            echo "Ensured admin@gmail.com has is_admin = true\n";
        }
    }
}
