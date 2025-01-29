<?php
// database/migrations/xxxx_xx_xx_create_people_housing_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleHousingTable extends Migration
{
    public function up()
    {
        Schema::create('people_housing', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('peopleId');
            $table->uuid('houseId');
            $table->uuid('responsibleId');
            $table->uuid('userId')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('peopleId')->references('id')->on('people')->onDelete('restrict');
            $table->foreign('houseId')->references('id')->on('housing')->onDelete('restrict');
            $table->foreign('responsibleId')->references('id')->on('people')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('people_housing');
    }
}
