<?php
// database/migrations/xxxx_xx_xx_create_levels_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', [1,2]);
            $table->unsignedBigInteger('parentId')->nullable();
            $table->timestamps();

            $table->foreign('parentId')->references('id')->on('parents')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
