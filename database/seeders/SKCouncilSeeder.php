<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\SKCouncil;
use App\Models\Youth;
use Illuminate\Database\Seeder;

class SKCouncilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = Barangay::all();

        // Generate 1 active SK Council per barangay
        foreach ($barangays as $barangay) {
            // Get available youths for this barangay
            $availableYouths = Youth::where('barangay_id', $barangay->id)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();

            if (count($availableYouths) < 7) {
                continue; // Skip if not enough youths for officers + kagawads
            }

            // Randomly select 7 youths (1 chairperson + 1 secretary + 1 treasurer + 4 kagawads)
            $selectedYouths = array_rand(array_flip($availableYouths), 7);
            if (! is_array($selectedYouths)) {
                $selectedYouths = [$selectedYouths];
            }

            $chairpersonId = $selectedYouths[0];
            $secretaryId = $selectedYouths[1];
            $treasurerId = $selectedYouths[2];
            $kagawadIds = array_slice($selectedYouths, 3, 4);

            // Create SK Council
            SKCouncil::create([
                'barangay_id' => $barangay->id,
                'term' => '2024-2025',
                'chairperson_id' => $chairpersonId,
                'secretary_id' => $secretaryId,
                'treasurer_id' => $treasurerId,
                'kagawad_ids' => $kagawadIds,
                'is_active' => true,
            ]);
        }
    }
}
