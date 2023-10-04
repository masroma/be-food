<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_apps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('logo')->nullable();
            $table->string('RAJAONGKIR_API_KEY')->nullable();
            $table->string('MIDTRANS_SERVERKEY')->nullable();
            $table->string('MIDTRANS_CLIENTKEY')->nullable();
            $table->string('ZENZIVA_USERKEY')->nullable();
            $table->string('ZENZIVA_PASSKEY')->nullable();
            $table->string('email_outlet')->nullable();
            $table->string('whatsapp_outlet')->nullable();
            $table->text('alamat_outlet')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_apps');
    }
}
