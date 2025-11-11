<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Youth;
use Illuminate\Database\Seeder;

class YouthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = Barangay::all();

        $firstNames = [
            'Maria', 'Juan', 'Jose', 'Rosa', 'Carlos', 'Ana', 'Miguel', 'Lucia',
            'Antonio', 'Angela', 'Ricardo', 'Carmen', 'Francisco', 'Guadalupe',
            'Luis', 'Dolores', 'Pedro', 'Isabel', 'Diego', 'Francisca',
            'Manuel', 'Juana', 'Ramon', 'Petra', 'Jesus', 'Antonia',
            'Gabriel', 'Magdalena', 'Enrique', 'Manuela', 'Ruben', 'Victoria',
        ];

        $lastNames = [
            'Garcia', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez',
            'Perez', 'Sanchez', 'Ramirez', 'Torres', 'Rivera', 'Cruz',
            'Flores', 'Morales', 'Gutierrez', 'Reyes', 'Medina', 'Aguilar',
            'Santos', 'Vargas', 'Castro', 'Ruiz', 'Delgado', 'Soto',
            'Silva', 'Acosta', 'Fuentes', 'Campos', 'Munoz', 'Herrera',
        ];

        $suffixes = ['Jr.', 'Sr.', 'II', 'III', null];

        $skills = [
            ['leadership', 'communication'],
            ['teamwork', 'problem-solving'],
            ['public speaking', 'event planning'],
            ['music', 'sports'],
            ['arts', 'crafts'],
            ['programming', 'coding'],
            ['cooking', 'baking'],
            ['teaching', 'tutoring'],
            ['community service', 'volunteering'],
            ['environmental activism'],
            ['social media management', 'digital marketing'],
            ['graphic design', 'web design'],
        ];

        $educationalLevels = [
            'Elementary',
            'High School',
            'College Undergraduate',
            'College Graduate',
            'Vocational',
            'Technical',
        ];

        $puroks = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', null];

        // Generate exactly 20 youths per barangay
        foreach ($barangays as $barangay) {
            $count = 20;

            for ($i = 0; $i < $count; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $suffix = $suffixes[array_rand($suffixes)];

                Youth::create([
                    'barangay_id' => $barangay->id,
                    'first_name' => $firstName,
                    'middle_name' => $firstNames[array_rand($firstNames)],
                    'last_name' => $lastName,
                    'suffix' => $suffix,
                    'date_of_birth' => now()->subYears(rand(15, 30))->subDays(rand(0, 365)),
                    'sex' => ['Male', 'Female', 'Other'][array_rand(['Male', 'Female', 'Other'])],
                    'purok' => $puroks[array_rand($puroks)],
                    'municipality' => 'Camalaniugan',
                    'province' => 'Cagayan',
                    'contact_number' => '09'.rand(100000000, 999999999),
                    'email' => strtolower($firstName.'.'.$lastName.'@example.com'),
                    'educational_attainment' => $educationalLevels[array_rand($educationalLevels)],
                    'skills' => $skills[array_rand($skills)],
                    'latitude' => 17.2667 + (rand(-500, 500) / 10000), // Camalaniugan approximate latitude
                    'longitude' => 121.7500 + (rand(-500, 500) / 10000), // Camalaniugan approximate longitude
                    'status' => rand(1, 10) <= 8 ? 'active' : 'archived', // 80% active
                    'remarks' => rand(1, 3) === 1 ? 'Seeded youth record for testing' : null,
                ]);
            }
        }
    }
}
