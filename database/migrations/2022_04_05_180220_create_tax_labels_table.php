<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_labels', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->string('label_name')->nullable();
            $table->string('label_code')->nullable();
            $table->integer('tax_label_status')->nullable();
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
        Schema::dropIfExists('tax_labels');
    }
}