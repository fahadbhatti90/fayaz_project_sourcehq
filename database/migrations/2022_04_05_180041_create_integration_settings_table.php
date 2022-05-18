<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_username')->nullable();
            $table->string('service_password')->nullable();
            $table->text('service_key')->nullable();
            $table->text('service_secret')->nullable();
            $table->integer('service_status')->nullable();
            $table->enum('service_env', ['Dev', 'UAT','Prod']);
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
        Schema::dropIfExists('integration_settings');
    }
}