<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'barangay'],
            ['name' => 'municipal'],
            ['name' => 'provincial'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
