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
        Schema::connection('db_read')->create('check_ign', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('user_id')->nullable();
            $table->string('other_id')->nullable();
            $table->string('nickname')->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ign');
    }
};
