<?php
// database/migrations/xxxx_xx_xx_create_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('imageProfile')->nullable(); // Path ke file foto profil
            $table->string('familyProfile')->nullable(); // Path ke file foto KK
            $table->string('ktpPhoto')->nullable(); // Path ke file foto KTP
            $table->string('selfiePhoto')->nullable(); // Path ke file foto selfie
            $table->json('extra')->nullable(); // Kolom extra tetap ada
            $table->uuid('userId')->nullable(); // ID pengguna terkait
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
