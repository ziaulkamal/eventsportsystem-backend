<?php
// database/migrations/xxxx_xx_xx_create_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticketId');
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->enum('ticket_type', ['regular', 'silver', 'gold', 'platinum']);
            $table->integer('quantity');
            $table->integer('used')->default(0);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'success', 'cancel'])->default('pending');
            $table->json('data')->nullable();
            $table->string('ticket_number')->unique();
            $table->uuid('userId')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('ticketId')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
