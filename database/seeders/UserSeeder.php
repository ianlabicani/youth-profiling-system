<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Municipal SK Official
        $municipalUser = User::create([
            'name' => 'Municipal SK Official',
            'email' => 'municipal@mail.com',
            'password' => Hash::make('11111111'),
        ]);
        $municipalUser->roles()->attach(Role::where('name', 'municipal')->first());
    }
}
