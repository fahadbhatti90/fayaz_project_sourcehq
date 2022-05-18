<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('sso_id')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('auth_code')->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('client_status')->nullable();
            $table->integer('client_role')->nullable();
            $table->string('profile_img')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('isConfirmed')->default(0);
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
        Schema::dropIfExists('clients');
    }
}
