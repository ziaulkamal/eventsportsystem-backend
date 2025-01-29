<?php
// database/migrations/xxxx_xx_xx_create_schedules_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->uuid('venueId');
            $table->uuid('sportClassId');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->uuid('userId')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('venueId')->references('id')->on('venues')->onDelete('restrict');
            $table->foreign('sportClassId')->references('id')->on('sport_classes')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
