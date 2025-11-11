<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BarangayEvent;
use App\Models\Barangay;
use App\Models\SKCouncil;

class BarangayEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = Barangay::all();

        $eventTemplates = [
            [
                'title' => 'Clean-Up Drive',
                'time' => '08:00',
                'venue' => 'Barangay Hall and surrounding areas',
                'organizer' => 'Sangguniang Kabataan',
                'description' => 'Community clean-up and tree planting activity.'
            ],
            [
                'title' => 'Youth Sports Festival',
                'time' => '09:00',
                'venue' => 'Barangay Sports Complex',
                'organizer' => 'Sangguniang Kabataan',
                'description' => 'Annual sports competition featuring basketball, volleyball, and badminton tournaments.'
            ],
            [
                'title' => 'Health and Wellness Program',
                'time' => '10:00',
                'venue' => 'Barangay Health Center',
                'organizer' => 'Barangay Health Office',
                'description' => 'Free medical consultations, vaccinations, and health awareness seminars.'
            ],
            [
                'title' => 'Youth Leadership Summit',
                'time' => '14:00',
                'venue' => 'Municipal Convention Center',
                'organizer' => 'Municipal Youth Office',
                'description' => 'Training and development program for youth leaders and advocates.'
            ],
            [
                'title' => 'Environmental Awareness Campaign',
                'time' => '07:00',
                'venue' => 'Barangay Hall',
                'organizer' => 'Environment Protection Team',
                'description' => 'Seminar on environmental conservation and waste management.'
            ]
        ];

        foreach ($barangays as $barangay) {
            // Get the SK Council for this barangay
            $skCouncil = SKCouncil::where('barangay_id', $barangay->id)->where('is_active', true)->first();

            foreach ($eventTemplates as $key => $template) {
                $daysToAdd = ($key + 1) * 7;

                BarangayEvent::create([
                    'barangay_id' => $barangay->id,
                    'sk_council_id' => $skCouncil?->id,
                    'title' => $template['title'],
                    'date' => now()->addDays($daysToAdd)->toDateString(),
                    'time' => $template['time'],
                    'venue' => $template['venue'],
                    'organizer' => $template['organizer'],
                    'description' => $template['description']
                ]);
            }
        }
    }
}
