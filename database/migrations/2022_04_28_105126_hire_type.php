<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HireType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hire_type', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('hire_type_status')->default(0);
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
        Schema::dropIfExists('hire_type');
    }
}
