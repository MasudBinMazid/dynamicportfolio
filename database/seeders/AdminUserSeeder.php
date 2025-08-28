<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rm.masud420@gmail.com'],
            [
                'name' => 'Masud',
                'password' => Hash::make('Masud@12'), // CHANGE THIS ASAP
                'is_admin' => true,
            ]
        );
    }
}
