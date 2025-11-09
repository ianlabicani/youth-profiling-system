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
        // Barangay SK Official
        $barangayUser = User::create([
            'name' => 'Barangay SK Official',
            'email' => 'barangay@mail.com',
            'password' => Hash::make('11111111'),
        ]);
        $barangayUser->roles()->attach(Role::where('name', 'BARANGAY')->first());

        // Municipal SK Official
        $municipalUser = User::create([
            'name' => 'Municipal SK Official',
            'email' => 'municipal@mail.com',
            'password' => Hash::make('11111111'),
        ]);
        $municipalUser->roles()->attach(Role::where('name', 'MUNICIPAL')->first());

        // Provincial SK Official
        $provincialUser = User::create([
            'name' => 'Provincial SK Official',
            'email' => 'provincial@mail.com',
            'password' => Hash::make('11111111'),
        ]);
        $provincialUser->roles()->attach(Role::where('name', 'PROVINCIAL')->first());
    }
}
