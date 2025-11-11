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
        Schema::table('barangay_events', function (Blueprint $table) {
            $table->foreignId('sk_council_id')->nullable()->constrained('sk_councils')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_events', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('barangay_events_sk_council_id_foreign');
            $table->dropColumn('sk_council_id');
        });
    }
};
