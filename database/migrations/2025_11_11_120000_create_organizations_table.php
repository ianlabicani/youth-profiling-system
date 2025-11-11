<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            // Key positions
            $table->unsignedBigInteger('president_id')->nullable();
            $table->unsignedBigInteger('vice_president_id')->nullable();
            $table->unsignedBigInteger('secretary_id')->nullable();
            $table->unsignedBigInteger('treasurer_id')->nullable();

            // Flexible structures
            $table->json('committee_heads')->nullable();
            $table->json('members')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
};
