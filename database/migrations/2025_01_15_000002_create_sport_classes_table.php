<?php
// database/migrations/xxxx_xx_xx_create_sport_classes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportClassesTable extends Migration
{
    public function up()
    {
        Schema::create('sport_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sportId');
            $table->enum('type', ['male', 'female']);
            $table->string('classOption');
            $table->uuid('imageId')->nullable();
            $table->uuid('userId')->nullable();
            $table->timestamps();

            $table->foreign('sportId')
                ->references('id')
                ->on('sports')
                ->onDelete('restrict');

        });
    }

    public function down()
    {
        Schema::dropIfExists('sport_classes');
    }


}
