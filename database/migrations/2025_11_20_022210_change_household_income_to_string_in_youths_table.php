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
            $table->string('household_income')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('youths', function (Blueprint $table) {
            $table->decimal('household_income', 10, 2)->nullable()->change();
        });
    }
};
