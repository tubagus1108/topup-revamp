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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_name')->nullable();
            $table->string('code')->nullable();
            $table->string('brand')->nullable();
            $table->string('status')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('banner')->nullable();
            $table->string('type')->nullable();
            $table->string('instruction')->nullable();
            $table->text('game_description')->nullable();
            $table->text('description_field')->nullable();
            $table->text('placeholder_id')->nullable();
            $table->text('placeholder_server')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
