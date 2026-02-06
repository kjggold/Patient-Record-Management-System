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
            ['email' => env('MAIN_ADMIN_EMAIL', 'winlaeshweyee636@gmailcom')],
            [
                'name'     => 'WinLae',
                'password' => 'mainadmin123', // hashed cast
                'role'     => 'main_admin',
                'status'   => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'eaindrakyaw887@gmail.com'],
            [
                'name'     => 'Eaindra',
                'password' => 'admin1234',
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'phooiechennie@gmail.com'],
            [
                'name'     => 'Phoo',
                'password' => 'admin1234',
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );
    }
}
