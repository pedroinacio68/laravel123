<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! is_string($email) || $email === '' || ! is_string($password) || strlen($password) < 8) {
            throw new RuntimeException('Configure ADMIN_EMAIL e ADMIN_PASSWORD (com pelo menos 8 caracteres) antes de executar db:seed.');
        }

        User::updateOrCreate(
            ['email' => mb_strtolower($email)],
            [
                'name' => config('admin.name'),
                'password' => $password,
                'role' => 'admin',
            ],
        );
    }
}
