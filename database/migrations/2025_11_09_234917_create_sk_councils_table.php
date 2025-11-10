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
        Schema::create('sk_councils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays')->cascadeOnDelete();
            $table->foreignId('chairperson_id')->nullable()->constrained('youths')->cascadeOnDelete();
            $table->foreignId('secretary_id')->nullable()->constrained('youths')->cascadeOnDelete();
            $table->foreignId('treasurer_id')->nullable()->constrained('youths')->cascadeOnDelete();
            $table->json('kagawad_ids')->nullable(); // Array of Youth IDs
            $table->timestamps();

            // Indexes
            $table->unique('barangay_id'); // Only one SK council per barangay
            $table->index('chairperson_id');
            $table->index('secretary_id');
            $table->index('treasurer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_councils');
    }
};
