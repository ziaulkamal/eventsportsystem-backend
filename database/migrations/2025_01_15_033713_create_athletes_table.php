<?php
// database/migrations/xxxx_xx_xx_create_athletes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAthletesTable extends Migration
{
    public function up()
    {
        Schema::create('athletes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('peopleId')->unique();
            $table->uuid('sportClassId')->nullable();
            $table->uuid('sportId');
            $table->decimal('height', 5, 2);
            $table->decimal('weight', 5, 2);
            $table->text('achievements')->nullable();
            $table->string('regionalRepresentative');
            $table->timestamps();

            // Foreign key untuk peopleId dengan onDelete('cascade')
            $table->foreign('peopleId')
                ->references('id')
                ->on('people')
                ->onDelete('cascade'); // Hapus athlete jika people dihapus

            // Foreign key untuk sportClassId dengan onDelete('restrict')
            $table->foreign('sportClassId')
                ->references('id')
                ->on('sport_classes')
                ->onDelete('restrict'); // Cegah hapus sport_class jika ada relasi

            // Foreign key untuk sportId dengan onDelete('restrict')
            $table->foreign('sportId')
                ->references('id')
                ->on('sports')
                ->onDelete('restrict'); // Cegah hapus sport jika ada relasi
        });
    }

    public function down()
    {
        Schema::dropIfExists('athletes');
    }
}
