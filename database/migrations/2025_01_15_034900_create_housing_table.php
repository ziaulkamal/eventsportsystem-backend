<?php
// database/migrations/xxxx_xx_xx_create_housing_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousingTable extends Migration
{
    public function up()
    {
        Schema::create('housing', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('location');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('phoneNumber');
            $table->integer('capacity')->nullable();
            $table->uuid('userId')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('housing');
    }
}
