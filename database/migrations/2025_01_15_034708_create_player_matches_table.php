<?php
// database/migrations/xxxx_xx_xx_create_player_matches_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('player_matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('athleteId');
            $table->uuid('scheduleId');
            $table->decimal('grade', 5, 2)->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->uuid('userId')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('athleteId')->references('id')->on('athletes')->onDelete('restrict');
            $table->foreign('scheduleId')->references('id')->on('schedules')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_matches');
    }
}
