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
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sport_id');
            $table->uuid('venue_id');
            $table->uuid('sport_class_id')->unique();
            $table->uuid('schedule_id')->nullable();
            $table->enum('status', [0, 1])->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('sport_class_id')->references('id')->on('sport_classes')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
