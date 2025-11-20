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
        $faker = \Faker\Factory::create('en_PH'); // Use Philippine locale

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
            $count = 10;

            for ($i = 0; $i < $count; $i++) {
                $firstName = $faker->firstName();
                $middleName = $faker->firstName();
                $lastName = $faker->lastName();
                $suffix = $suffixes[array_rand($suffixes)];
                $sex = $faker->randomElement(['Male', 'Female', 'Other']);

                // Generate guardians (1-2 guardians)
                $guardiansCount = rand(1, 2);
                $guardians = [];
                for ($g = 0; $g < $guardiansCount; $g++) {
                    $guardians[] = [
                        'first_name' => $faker->firstName(),
                        'middle_name' => rand(0, 1) ? $faker->firstName() : null,
                        'last_name' => $faker->lastName(),
                    ];
                }

                // Generate siblings (1-5 siblings)
                $siblingsCount = rand(1, 5);
                $siblings = [];
                for ($s = 0; $s < $siblingsCount; $s++) {
                    $siblings[] = [
                        'first_name' => $faker->firstName(),
                        'middle_name' => rand(0, 1) ? $faker->firstName() : null,
                        'last_name' => $lastName, // Same last name as youth
                    ];
                }

                // Generate household income (50% have income data)
                $householdIncome = rand(1, 2) ? $faker->numberBetween(5000, 50000) : null;

                Youth::create([
                    'barangay_id' => $barangay->id,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'suffix' => $suffix,
                    'date_of_birth' => $faker->dateTimeBetween('-30 years', '-15 years'),
                    'sex' => $sex,
                    'purok' => $puroks[array_rand($puroks)],
                    'municipality' => 'Camalaniugan',
                    'province' => 'Cagayan',
                    'contact_number' => $faker->numerify('09#########'),
                    'email' => $faker->unique()->safeEmail(),
                    'guardians' => count($guardians) > 0 ? $guardians : null,
                    'siblings' => count($siblings) > 0 ? $siblings : null,
                    'household_income' => $householdIncome,
                    'educational_attainment' => $educationalLevels[array_rand($educationalLevels)],
                    'skills' => $skills[array_rand($skills)],
                    'latitude' => 18.2753372 + $faker->randomFloat(4, -0.03, 0.03), // Camalaniugan center with ±0.03° radius (~3.3km)
                    'longitude' => 121.6967438 + $faker->randomFloat(4, -0.03, 0.03), // Camalaniugan center with ±0.03° radius (~3.3km)
                    'status' => $faker->randomElement(['active', 'active', 'active', 'active', 'active', 'active', 'active', 'active', 'archived', 'archived']), // 80% active
                    'remarks' => $faker->optional(0.33)->sentence(),
                ]);
            }
        }
    }
}
