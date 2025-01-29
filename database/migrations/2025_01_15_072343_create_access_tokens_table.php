<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTokensTable extends Migration
{
    public function up()
    {
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('access_token');
            $table->integer('hit_count')->default(0);  // Hit counter
            $table->timestamp('expires_at');  // Waktu kadaluarsa token
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_tokens');
    }
}
