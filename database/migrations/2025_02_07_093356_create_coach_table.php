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
        Schema::create('coach', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('peopleId')->unique();
            $table->enum('role', ['coach', 'coach_asisten', 'official', 'official_asisten']);
            $table->uuid('sportId');
            $table->string('regionalRepresentative');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach');
    }
};
