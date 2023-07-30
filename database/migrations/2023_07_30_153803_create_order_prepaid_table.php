<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_prepaid', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('customer_no');
            $table->integer('id_service')->nullable();
            $table->bigInteger('price')->nullable();
            $table->integer('profit')->nullable();
            $table->string('sid')->nullable();
            $table->enum('status', ['Success', 'Pending', 'Fail'])->default('Pending');
            $table->text('desc')->nullable();
            $table->string('transaction_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_prepaid');
    }
};
