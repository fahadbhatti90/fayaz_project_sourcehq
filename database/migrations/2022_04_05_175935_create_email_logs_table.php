<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->enum('user_type', ['Client', 'Contractor','Product Administrator']);
            $table->integer('user_id')->nullable();
            $table->string('email_address')->nullable();
            $table->longText('email_subject')->nullable();
            $table->longText('email_content')->nullable();
            $table->integer('email_status')->nullable();
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
        Schema::dropIfExists('email_logs');
    }
}