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
            $table->foreignId('barangay_id')->nullable()->after('photo')->constrained('barangays')->cascadeOnDelete();
            $table->boolean('is_sk_member')->default(false)->after('barangay_id');

            // Index for barangay_id
            $table->index('barangay_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('youths', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['barangay_id']);
            $table->dropColumn(['barangay_id', 'is_sk_member']);
        });
    }
};
