<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->longText('measurement_details')->nullable();
            $table->longText('male_measurement_details')->nullable();
            $table->longText('female_measurement_details')->nullable();
            $table->string('business_focus')->nullable();
            $table->integer('measurement_set')->default(0);
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_payment_advice')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('sms_api_username')->nullable();
            $table->string('sms_api_key')->nullable();
            $table->string('sms_sender')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
