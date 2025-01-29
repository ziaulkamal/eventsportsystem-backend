<?php
// database/migrations/xxxx_xx_xx_create_tickets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('regular_quota');
            $table->integer('regular_price');
            $table->integer('silver_quota')->nullable();
            $table->integer('silver_price')->nullable();
            $table->integer('gold_quota')->nullable();
            $table->integer('gold_price')->nullable();
            $table->integer('platinum_quota')->nullable();
            $table->integer('platinum_price')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->uuid('userId')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
