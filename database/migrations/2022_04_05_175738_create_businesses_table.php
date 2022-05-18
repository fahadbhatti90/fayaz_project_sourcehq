<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->longText('business_mission')->nullable();
            $table->string('business_url')->nullable();
            $table->integer('crm_business')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('career_portal')->nullable();
            $table->string('sub_domain')->nullable();
            $table->enum('business_compliance', ['Yes', 'No']);
            $table->integer('business_country')->nullable();
            $table->integer('business_state')->nullable();
            $table->longText('sso_meta_data')->nullable();
            $table->string('logo')->nullable();
            $table->string('company_number')->nullable();
            $table->string('vat_tax_id')->nullable();
            $table->year('founding_year');
            $table->integer('language')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('business_status')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}