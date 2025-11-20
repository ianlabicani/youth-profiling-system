<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('youths', function (Blueprint $table) {
            // Guardian information (JSON array with up to 2 guardians)
            // Each guardian has: first_name, middle_name, last_name
            $table->json('guardians')->nullable()->after('email');

            // Sibling names (JSON array)
            // Each sibling has: first_name, middle_name, last_name
            $table->json('siblings')->nullable()->after('guardians');

            // Household income
            $table->decimal('household_income', 10, 2)->nullable()->after('siblings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('youths', function (Blueprint $table) {
            $table->dropColumn(['guardians', 'siblings', 'household_income']);
        });
    }
};
