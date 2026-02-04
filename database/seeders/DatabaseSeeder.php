<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fixed admins
        User::updateOrCreate(
            ['email' => env('MAIN_ADMIN_EMAIL', 'mainadmin@clinic.com')],
            [
                'name'     => 'Main Admin',
                'password' => 'mainadmin123', // hashed cast
                'role'     => 'main_admin',
                'status'   => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin1@clinic.com'],
            [
                'name'     => 'Admin One',
                'password' => 'admin1234',
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@clinic.com'],
            [
                'name'     => 'Admin Two',
                'password' => 'admin1234',
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );
    }
}
