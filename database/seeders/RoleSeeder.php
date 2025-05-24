<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Config;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Config::get('constants.roles');

        foreach ($roles as $role) {
            $role = Role::updateOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]); 
        }
    }
}
