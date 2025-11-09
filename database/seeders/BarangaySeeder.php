<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangayRole = Role::firstOrCreate(['name' => 'barangay']);

        $barangays = [
            'Abagao',
            'Afunan-Cabayu',
            'Agusi',
            'Alilinu',
            'Baggao',
            'Bantay',
            'Bulala',
            'Casili Norte',
            'Casili Sur',
            'Catotoran Norte',
            'Catotoran Sur',
            'Centro Norte (Poblacion)',
            'Centro Sur (Poblacion)',
            'Cullit',
            'Dacal-la Fugu',
            'Dammang Norte / Joaquin dela Cruz',
            'Dammang Sur / Felipe Tuzon',
            'Dugo',
            'Fusina',
            'Gen. Eduardo Batalla',
            'Jurisdiccion',
            'Luec',
            'Minanga',
            'Paragat',
            'Sapping',
            'Tagum',
            'Tuluttuging',
            'Ziminila',
        ];

        foreach ($barangays as $barangayName) {
            // Create barangay
            $barangay = Barangay::create(['name' => $barangayName]);

            // Create admin user for the barangay
            $emailSlug = Str::slug($barangayName, '');
            $user = User::create([
                'name' => $barangayName.' Administrator',
                'email' => $emailSlug.'@barangay.local',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            // Assign barangay role
            $user->roles()->attach($barangayRole);

            // Connect user to barangay
            $barangay->users()->attach($user);
        }
    }
}
