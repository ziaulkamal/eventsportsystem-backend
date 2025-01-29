<?php
// database/migrations/xxxx_xx_xx_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('peopleId')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('levelId');
            $table->enum('status', ['active', 'pending', 'banned'])->default('pending');
            $table->string('activationNumber')->nullable();
            $table->timestamp('lastLogin')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('peopleId')->references('id')->on('people')->onDelete('restrict');
            $table->foreign('levelId')->references('id')->on('levels')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
