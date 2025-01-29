<?php
// database/migrations/xxxx_xx_xx_create_people_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullName');
            $table->integer('age')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('identityNumber')->unique();
            $table->string('familyIdentityNumber')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('streetAddress')->nullable();
            $table->integer('religion')->nullable();
            $table->unsignedBigInteger('provinceId')->nullable();
            $table->unsignedBigInteger('regencieId')->nullable();
            $table->unsignedBigInteger('districtId')->nullable();
            $table->unsignedBigInteger('villageId')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('email')->nullable();
            $table->uuid('documentId')->nullable();
            $table->uuid('userId')->nullable();
            $table->timestamps();

            $table->foreign('documentId')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
