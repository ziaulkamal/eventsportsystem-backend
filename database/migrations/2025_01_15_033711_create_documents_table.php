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
            $table->string('docsKtp')->nullable(); // Path ke file foto profil
            $table->string('docsIjazah')->nullable(); // Path ke file foto KK
            $table->string('docsSim')->nullable(); // Path ke file foto KTP
            $table->string('docsAkte')->nullable(); // Path ke file foto selfie
            $table->string('docsTransport')->nullable(); // Path ke file foto selfie
            $table->string('docsSelfieKtp')->nullable(); // Path ke file foto selfie
            $table->string('docsImageProfile')->nullable(); // Path ke file foto selfie

            $table->uuid('userId')->nullable(); // ID pengguna terkait
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
