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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
            $table->string('service')->nullable();
            $table->string('sid')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('price_member')->nullable();
            $table->bigInteger('price_platinum')->nullable();
            $table->bigInteger('big_gold')->nullable();
            $table->integer('profit')->nullable();
            $table->integer('profit_member')->nullable();
            $table->integer('profit_platinum')->nullable();
            $table->integer('profit_gold')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->nullable();
            $table->string('provider')->nullable();
            $table->string('product_logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
