<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->nullable();
            $table->string('job_title')->nullable();
            $table->integer('job_category')->nullable();
            $table->integer('job_department')->nullable();
            $table->integer('job_position')->nullable();
            $table->string('job_openings')->nullable();
            $table->integer('job_location')->nullable();
            $table->integer('job_remote')->nullable();
            $table->string('job_contract_type')->nullable();
            $table->string('job_project_sow')->nullable();
            $table->string('job_pay_type')->nullable();
            $table->string('job_annual_bill_rate')->nullable();
            $table->string('job_min_bill_rate')->nullable();
            $table->integer('job_max_bill_rate')->nullable();
            $table->integer('job_annual_pay_rate')->default(0);
            $table->integer('job_min_pay_rate')->nullable();
            $table->integer('job_annual_may_rate')->nullable();
            $table->timestamp('job_start_date')->useCurrent = true;
            $table->timestamp('job_end_date')->useCurrent = true;
            $table->timestamp('job_hire_date')->useCurrent = true;
            $table->string('job_exclude_holidays')->nullable();
            $table->integer('job_working_days')->nullable();
            $table->timestamp('job_tentative_end_date')->useCurrent = true;
            $table->string('job_description')->nullable();
            $table->string('job_description_1')->nullable();
            $table->string('job_description_2')->nullable();
            $table->string('job_internal_notes')->nullable();
            $table->string('job_career_web_internal')->nullable();
            $table->string('job_career_web_external')->nullable();
            $table->integer('hiring_manager')->default(0);
            $table->integer('hiring_manager_1')->nullable();
            $table->integer('job_status')->nullable();
            $table->integer('job_created_by')->nullable();
            //$table->integer('job_portal_user')->nullable();
            $table->enum('job_portal_user', ['Client','Program','Product Administrator']);
            $table->timestamp('job_date_created')->useCurrent = true;
            $table->timestamp('job_date_published')->useCurrent = true;
            //$table->date('job_date_updated')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('job_date_updated')->useCurrent = true;
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
        Schema::dropIfExists('jobs');
    }
}
