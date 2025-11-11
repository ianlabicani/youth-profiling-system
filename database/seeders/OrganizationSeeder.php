<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Youth;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationNames = [
            'Youth Development Council',
            'Community Leaders Association',
            'Environmental Protection Group',
            'Sports and Recreation Club',
            'Arts and Culture Society',
            'Educational Support Network',
            'Health and Wellness Initiative',
            'Agricultural Youth Collective',
            'Digital Skills Academy',
            'Peace and Unity Movement',
        ];

        $descriptions = [
            'Focused on developing youth potential through leadership programs and workshops.',
            'Promoting community engagement and local governance participation.',
            'Dedicated to environmental sustainability and conservation efforts.',
            'Organizing sports events and recreational activities for youth.',
            'Celebrating and preserving local arts and cultural traditions.',
            'Supporting educational advancement and academic excellence.',
            'Promoting health, wellness, and prevention awareness programs.',
            'Advancing agricultural practices and rural development.',
            'Teaching digital literacy and technology skills to youth.',
            'Building peace and unity among diverse community groups.',
        ];

        // Get all available youths
        $allYouths = Youth::all()->pluck('id')->toArray();

        if (count($allYouths) < 4) {
            return; // Need at least 4 youths for officers
        }

        // Generate 10 organizations (not connected to barangay)
        for ($i = 0; $i < 10; $i++) {
            // Randomly select officers from all available youths
            $officers = array_rand(array_flip($allYouths), min(4, count($allYouths)));
            if (!is_array($officers)) {
                $officers = [$officers];
            }

            $presidentId = $officers[0] ?? $allYouths[array_rand($allYouths)];
            $vicePresidentId = $officers[1] ?? $allYouths[array_rand($allYouths)];
            $secretaryId = $officers[2] ?? $allYouths[array_rand($allYouths)];
            $treasurerId = isset($officers[3]) ? $officers[3] : $allYouths[array_rand($allYouths)];

            // Get youth names for committee heads
            $presidentYouth = Youth::find($presidentId);
            $vicePresidentYouth = Youth::find($vicePresidentId);

            // Create committee heads
            $committeeHeads = [];
            $committeeHeads[] = [
                'head_id' => $presidentId,
                'name' => $presidentYouth->full_name ?? 'Committee Head',
                'position' => 'President',
            ];

            if (rand(1, 2) === 1) {
                $committeeHeads[] = [
                    'head_id' => $vicePresidentId,
                    'name' => $vicePresidentYouth->full_name ?? 'Committee Head',
                    'position' => 'Vice President',
                ];
            }

            // Create random additional committee heads
            for ($j = 0; $j < rand(1, 2); $j++) {
                $randomYouthId = $allYouths[array_rand($allYouths)];
                $randomYouth = Youth::find($randomYouthId);
                $committeeHeads[] = [
                    'head_id' => $randomYouthId,
                    'name' => $randomYouth->full_name ?? 'Committee Head',
                    'position' => 'Member',
                ];
            }

            // Create organization (no barangay_id)
            $organization = Organization::create([
                'name' => $organizationNames[$i],
                'president_id' => $presidentId,
                'vice_president_id' => $vicePresidentId,
                'secretary_id' => $secretaryId,
                'treasurer_id' => $treasurerId,
                'description' => $descriptions[$i],
                'committee_heads' => $committeeHeads,
                'members' => [],
            ]);

            // Add random members (5-10 members per organization)
            $memberCount = rand(5, 10);
            $selectedMembers = array_rand(array_flip($allYouths), min($memberCount, count($allYouths)));

            if (!is_array($selectedMembers)) {
                $selectedMembers = [$selectedMembers];
            }

            $members = [];
            foreach ($selectedMembers as $memberId) {
                $members[] = $memberId;
            }

            $organization->update(['members' => $members]);
        }
    }
}
