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
        Schema::create('setting_webs', function (Blueprint $table) {
            $table->id();
            $table->text('judul_web')->default("WEBSITE TOP UP GAMING");
            $table->text('deskripsi_web')->default("Top Up Games, Voucher & PPOB Online 24 Jam");
            $table->string('logo_header')->nullable();
            $table->string('logo_footer')->nullable();
            $table->string('logo_favicon')->nullable();
            $table->text('url_wa');
            $table->text('url_ig');
            $table->text('url_tiktok');
            $table->text('url_youtube');
            $table->text('url_fb');
            $table->text('warna1')->default("#141414");
            $table->text('warna2')->default("#141414");
            $table->text('warna3')->default("#212121");
            $table->text('warna4')->default("#E7E7E7");
            $table->string('tripay_api')->nullable();
            $table->string('tripay_merchant_code')->nullable();
            $table->string('tripay_private_key')->nullable();
            $table->string('username_digi')->nullable();
            $table->string('api_key_digi')->nullable();
            $table->string('apigames_secret')->nullable();
            $table->string('apigames_merchant')->nullable();
            $table->string('vip_apiid')->nullable();
            $table->string('vip_apikey')->nullable();
            $table->string('nomor_admin')->nullable();
            $table->string('wa_key')->nullable();
            $table->string('wa_number')->nullable();
            $table->string('ovo_admin')->nullable();
            $table->string('ovo1_admin')->nullable();
            $table->string('gopay_admin')->nullable();
            $table->string('gopay1_admin')->nullable();
            $table->string('dana_admin')->nullable();
            $table->string('shopeepay_admin')->nullable();
            $table->string('bca_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_webs');
    }
};
