<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('location_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('tax_label_id')->nullable();
            $table->float('tax_percentage',10,4);
            $table->integer('location_tax_status')->default(0);
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
        Schema::dropIfExists('location_taxes');
    }
}
