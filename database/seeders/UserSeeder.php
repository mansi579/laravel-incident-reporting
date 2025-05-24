<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Config::get('constants.roles');

        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@yopmail.com'],
            [
                'name' => 'Super-admin',
                'password' => Hash::make('123456'),
            ]
        );
        $superAdmin->assignRole($roles['super-admin']);

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@yopmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
            ]
        );
        $admin->assignRole($roles['admin']);

        // User
        $user = User::updateOrCreate(
            ['email' => 'mansi@yopmail.com'],
            [
                'name' => 'Mansi',
                'password' => Hash::make('123456'),
            ]
        );
        $user->assignRole($roles['user']);
    }
}
