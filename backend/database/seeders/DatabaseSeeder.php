<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial records.
     */
    public function run(): void
    {
        // Seed default Admin User for local development and testing
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@smartcms.com',
            'password' => Hash::make('password'), // Store hashed password securely
            'role'     => 'admin', // Access level admin
        ]);

        // Seed default Editor User for local development and testing
        User::create([
            'name'     => 'Editor User',
            'email'    => 'editor@smartcms.com',
            'password' => Hash::make('password'),
            'role'     => 'editor', // Access level editor
        ]);
    }
}
